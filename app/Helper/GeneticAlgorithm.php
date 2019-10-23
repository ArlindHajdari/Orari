<?php

namespace App\Helper;

use App\Models\Lush;
use App\Models\Group;
use Carbon\Carbon;
use App\Helper\Cartesian;

class GeneticAlgorithm{
    public $mutation_rate;
    public $crossoverRateValue;
    public $halls;
    public $semester;
    public $lush;
    public $teachers;
    public $probabilityOfSwapping;
    public $days;
    public $startTime;
    public $duration;
    public $totalFitness;
    public $TDHCartesian;

    public function __construct($semester,$mutation_rate=0.2,$crossoverRateValue=0.25,$probabilityOfSwapping=0.5){
        $this->semester = $semester;
        $this->mutation_rate = $mutation_rate;
        $this->crossoverRateValue = $crossoverRateValue;
        $this->teachers = getTeachers($this->semester);
        $this->halls = getHalls();
        $this->lush = Lush::select('id')->pluck('id')->toArray();
        $this->probabilityOfSwapping = $probabilityOfSwapping;
        $this->days = getDays();
        $this->startTime = getPossibleTime();
        $this->duration = [1,2,3];
        $this->totalFitness = (count($this->days)/2) + (count($this->startTime)/5);
        $this->TDHCartesian = $this->getTimeDayHallCartesian();
    }

    //Gjenerimi i popullacionit
    public function generateChromosomes(){
        $temp = array();
        $count = 0;
        for($i=0; $i < count($this->teachers); $i++){
            $temp_Subject = getSubjects($this->semester,$this->teachers[$i]);
            foreach($temp_Subject as $subject){
                $lush = getLush($this->teachers[$i],$subject);
                $groupsPerSubjectAndLUSH = getGroupsFromLushAndSubject($subject,$lush);
                $duration = getDurationFromCPSLush(getCPS($this->teachers[$i],$subject), $lush, $this->duration);
                foreach($groupsPerSubjectAndLUSH as $group){
                    for($j = 0; $j < 3; $j++){
                        $temp[$count][0] = intToString($this->teachers[$i]);
                        $temp[$count][1] = intToString($subject);
                        $temp[$count][2] = $lush;
                        $temp[$count][3] = intToString($this->halls[array_rand($this->halls)]);
                        $temp[$count][4] = $group;
                        $temp[$count][5] = array_rand($this->days);
                        $temp[$count][6] = array_rand($this->startTime);
                        $temp[$count][7] = $duration;

                        $count++;
                    }
                }
            }
        }

        //Grupimi në bazë të mësimdhënësit
        $temp = groupBy_SameKey($temp,0);

        //Grupimi në bazë të lëndës
        $array = array();
        foreach($temp as $key=>$element){
            $array[$key] = groupBy_SameKey($element,1);
        }

        //Grupimi në bazë të grupit
        $array1 = array();
        foreach($array as $key=>$element){
            foreach($element as $key1=>$element1){
                $array1[$key][$key1] = groupBy_SameKey($element1,4);
            }
        }

        return $array1;
    }

    //Fitnesi per një kromozom
    public function objectiveChromFunction($chromosome){
        return timeCost((int)$chromosome[0],$chromosome[6])+dayCost((int)$chromosome[0],$chromosome[5]);
    }

    public function getGroupFitnesses($population){
        $temp = array();
        foreach($population as $key=>$teachers){
            foreach($teachers as $key1=>$subjects){
                foreach($subjects as $key2=>$groups){
                    foreach($groups as $key3=>$chromosome){
                        $temp[$key.'-'.$key1.'-'.$key2.'-'.$key3] = $this->objectiveChromFunction($chromosome);
                    }
                }
            }
        }

        return $temp;
    }

    public function getGroupChromosomes($population){
        $temp = array();
        foreach($population as $key=>$teachers){
            foreach($teachers as $key1=>$subjects){
                foreach($subjects as $key2=>$groups){
                    foreach($groups as $chromosome){
                        $temp[$key.'-'.$key1.'-'.$key2][] = $chromosome;
                    }
                }
            }
        }

        return $temp;
    }

    //Merr grupin e chromosomeve per indexin e caktuar
    public function getSetFromKey($chromosomesInOrder, $teacherSubjectGroupKey){
        $temp = array();
        foreach($chromosomesInOrder as $key=>$chromosome){
            if($teacherSubjectGroupKey == $key){
                $temp = $chromosome;
            }
        }
        return $temp;
    }

    public function getGroupFittest($groupsChromosomes){
        $fittest = $groupsChromosomes[array_keys($groupsChromosomes)[0]];
        foreach($groupsChromosomes as $group){
            if($this->objectiveChromFunction($fittest) >= $this->objectiveChromFunction($group)){
                $fittest = $group;
            }
        }
        return $fittest;
    }

    //Fitness funksioni për një mësimdhënës
    public function objectiveTeacherFunction($chromosomes){
        $groupFitness = 0;
        $subjectFitness = 0;
        foreach($chromosomes as $subjects){
            $countS = 0;
            foreach($subjects as $groups){
                $count = 0;
                foreach($groups as $chromosome){
                    $groupFitness += $this->objectiveChromFunction($chromosome);
                    $count++;
                }
                $subjectFitness += $groupFitness/$count;
                $countS++;
            }
            $subjectFitness = $subjectFitness/$countS;
        }
        
        return (1-(($subjectFitness)/($this->totalFitness*2)))*100;
    }

    //funksioni per marrjen e fitnesit te profesoreve
    public function getTeacherObjectiveFunction($population){
        $temp = array();
        foreach($population as $key=>$teacher){
            $temp[$key] = $this->objectiveTeacherFunction($teacher);
        }

        return $temp;
    }

    //Fitnesi per perqindjen e profesoreve
    public function objectiveTeachersFunction($population){
        $negativeFitness_Teachers = 0;
        $sumOfTeachersFitness = 0;
        $temp = $this->getTeacherObjectiveFunction($population);
        foreach($temp as $chromosome){
            if($chromosome > 40 && $chromosome < 80){
                continue;
            }elseif($chromosome < 40){
                $negativeFitness_Teachers++;
                $sumOfTeachersFitness+=($chromosome - 40);
            }elseif($chromosome > 80){
                $sumOfTeachersFitness+=($chromosome - 80);
            }
        }
        $nTPercentage = (1 - ($negativeFitness_Teachers/count($temp)))*40;
        $sTPersentage = ($sumOfTeachersFitness/(count($temp)*20))*60;

        return $nTPercentage+$sTPersentage;
    }

    //Array me krejt fitnessat e kromozomeve
    //Duhet mu ndrru per arsye te struktures se matrices se popullacionit
    public function objectiveFunction($population){
        $temp = array();
        foreach($population as $chromosome){
            $temp[] = $this->objectiveChromFunction($chromosome);
        }
        return $temp;
    }

    //Prej arrayt te fitnessit te pergjithshem
    public function getFittestFitness($objAllFunc){
        return min($objAllFunc);
    }

    //Kromozomi me i mire prej nje grupit te kromozomeve
    public function getFittestTeacherSubjectFitness($teacherSubjectGroup){
        $fittest = $teacherSubjectGroup[array_keys($teacherSubjectGroup)[0]];
        foreach($teacherSubjectGroup as $chromosome){
            if($this->objectiveChromFunction($fittest) >= $this->objectiveChromFunction($chromosome)){
                $fittest = $chromosome;
            }
        }

        return $fittest;
    }

    //Produkti kartezian ne mes diteve, kohes se fillimit, dhe sallave
    public function getTimeDayHallCartesian(){
        return Cartesian::build([array_keys($this->days),array_keys($this->startTime),array_keys($this->halls)]);
    }

    //Kshyr nese nuk kane mbete mjaftueshem kohe psh 6:30 - 8:45
    public function isTDHCAvailable($currentTDHCartesian, $day, $time, $hall, $duration){
        $loopTo = null;

        if($duration <= 1){
            $loopTo = 2;
        }elseif($duration <= 2){
            $loopTo = 3;
        }else{
            $loopTo = 5;
        }



        $lastKey = array_keys(array_reverse($currentTDHCartesian,true))[0];

        $key = TDHCExists($currentTDHCartesian,array($day, $time, $hall));

        if($key == -1){
            return false;
        }elseif($lastKey < ($key+$loopTo)){
            return false;
        }

        for ($i=$key; $i < $key+$loopTo; $i++) {
            if(array_key_exists($i,$currentTDHCartesian)){
                if(TDHCExists($currentTDHCartesian,$currentTDHCartesian[$i]) != -1){
                    continue;
                }else{
                    return false;
                }
            }else{
                return false;
            }
        }

        return true;
    }

    //Largo prej produktit kartezian kohet
    public function updateTDHCartesian(&$currentCartesian, $day, $time, $hall, $duration){
        $loopTo = null;
        if($duration == 1){
            $loopTo = 2;
        }elseif($duration == 2){
            $loopTo = 3;
        }else{
            $loopTo = 5;
        }

        $key = TDHCExists($currentCartesian, array($day, $time, $hall));

        for ($i=$key; $i < $key + $loopTo; $i++) {
            unset($currentCartesian[$i]);
        }
    }

    //Merr kromozomin me fitness me te mire
    public function getFittest($population){
        $fittest = $population[0];
         for ($i = 0; $i < count($population); $i++) {
             if ($this->objectiveChromFunction($fittest) >= $this->objectiveChromFunction($population[$i])) {
                 $fittest = $population[$i];
             }
         }
         return $fittest;
    }

    //Fitness funksioni i popullacionit(population eshte objFunc i popullacionit)
    public function fitnessFunction($population){
        $temp = array();
        foreach($population as $chromosome){
            $temp[] = 1/(1+$chromosome);
        }

        return $temp;
    }

    public function additiveNTimes($population,$n){
        $total = 0;
        for ($i=0; $i < $n; $i++) {
            $total = $total + $population[$i];
        }

        return $total;
    }

    //Gjenero prinderit ne baze te fitnes funksionit te tyre
    //I pari me fitnes me te mire, dhe i dyti random
    public function getParents($teacherSubjectGroupOfChromosomes, $level){
        if($level < 200){
            $temp = $teacherSubjectGroupOfChromosomes;
            $lowestFitness = [$this->objectiveChromFunction($temp[array_keys($temp)[0]]),array_keys($temp)[0]];

            foreach ($temp as $key => $value) {
                if($lowestFitness[0] > $this->objectiveChromFunction($value)){
                    $lowestFitness = [$this->objectiveChromFunction($value), $key];
                }
            }

            unset($temp[$lowestFitness[1]]);

            $secondRandomParent = $temp[array_rand($temp)];

            return [$teacherSubjectGroupOfChromosomes[$lowestFitness[1]],$secondRandomParent];
        }else{
            $chromosome = $teacherSubjectGroupOfChromosomes[0];
            $teacher = (int)$chromosome[0];
            $subject = (int)$chromosome[1];
            $group = $chromosome[4];
            $lush = $chromosome[2];
            $duration = $chromosome[7];

            $tempi = array();

            for($j = 0; $j < 3; $j++){
                $tempi[$j][0] = intToString($teacher);
                $tempi[$j][1] = intToString($subject);
                $tempi[$j][2] = $lush;
                $tempi[$j][3] = intToString($this->halls[array_rand($this->halls)]);
                $tempi[$j][4] = $group;
                $tempi[$j][5] = array_rand($this->days);
                $tempi[$j][6] = array_rand($this->startTime);
                $tempi[$j][7] = $duration;
            }

            $level = 0;
            return $this->getParents($tempi, $level);
        }
    }

    public function getCutPointsForParents($parents,$numberOfPoints=2){
        $C = array();
        for ($i=2; $i < $numberOfPoints; $i++) {
            $C[] = rand(2,count($parents[array_keys($parents)[0]])-1);
        }
        return $C;
    }

    public function crossover($parents){
        $firstChromosome = $parents[0];
        $secondChromosome = $parents[1];
        $temp = array();

        for ($i=0; $i < 5; $i++) {
            $temp[] = $firstChromosome[$i];
        }

        for ($i=5; $i < count($firstChromosome); $i++) {
            if($i%2==1){
                $temp[] = $secondChromosome[$i];
            }else{
                $temp[] = $firstChromosome[$i];
            }
        }

        return $temp;
    }

    public function multiPointCrossover($firstChromosome,$secondChromosome,$cutPoints,$startPoint = 0){
        $newChromosome = array();
        $cutPoints = sort($cutPoints);

        for ($i=$startPoint; $i < count($cutPoints); $i++) {
            if($i != (count($cutPoints)-1)){
                for($j = $cutPoints[$i]; $j < $cutPoints[$i+1]; $j++){
                    $newChromosome[] = ($i%2==0) ? $firstChromosome[$j]  : $secondChromosome[$j];
                }
            }else{
                //i fundit
                $newChromosome[] = ($i%2==0) ? $firstChromosome[$i]  : $secondChromosome[$i];
            }
        }

        return $newChromosome;
    }

    public function random(){
         return (float)rand()/(float)getrandmax();
    }

    public function uniformCrossover($parents,$keys=[3,5,6,7]){
        $firstChromosome = $parents[0];
        $secondChromosome = $parents[1];

        $offSpring = $firstChromosome;

        for ($i=$keys[0]; $i < count($firstChromosome); $i++) {
            if(in_array($i,$keys)){
                if($this->random() <= $this->probabilityOfSwapping){
                    //swap genes
                    $offSpring[$i] = $secondChromosome[$i];
                }else{
                    //don't swap genes
                    $offSpring[$i] = $firstChromosome[$i];
                }
            }
        }

        return $offSpring;
    }

    public function crossoverParents($parents,$crossoverType='uniform'){
        $temp = array();
        $keys = array_keys($parents);
        if($crossoverType == 'uniform'){
            foreach(array_keys($keys) as $key){
                if($keys[$key] != end($keys)){
                    $temp[$keys[$key]] = $this->uniformCrossover($parents[$keys[$key]],$parents[$keys[$key+1]],$this->probabilityOfSwapping);
                }else{
                    $temp[$keys[$key]] = $this->uniformCrossover($parents[$keys[$key]],$parents[$keys[0]],$this->probabilityOfSwapping);
                }
            }
        }elseif($crossoverType == 'multi-point'){
            $C = $this->getCutPointsForParents($parents);

            foreach(array_keys($keys) as $key){
                if($keys[$key] != end($keys)){
                    $temp[$keys[$key]] = $this->multiPointCrossover($parents[$keys[$key]],$parents[$keys[$key+1]],$C);
                }else{
                    $temp[$keys[$key]] = $this->multiPointCrossover($parents[$keys[$key]],$parents[$keys[0]],$C);
                }
            }
        }
        return $temp;
    }

    public function mutation($population){
        $totalGens = count($population) * count($population[array_keys($population)[0]]);
        $numberOfMutations = floor($this->mutation_rate * $totalGens);
        $geneIndexes = array();

        for($i = 0; $i < $numberOfMutations; $i++){
            $geneIndexes[] = floor(rand(0,$totalGens-1));
        }
        $geneIndexes = array_unique($geneIndexes);
        for($i = 0; $i < count($geneIndexes); $i++){
            $chromosomeNumber =  floor(array_values($geneIndexes)[$i]/count($population[array_keys($population)[0]]));
            $geneOfChromosome = array_values($geneIndexes)[$i]%count($population[array_keys($population)[0]]);
            // dump($population[$chromosomeNumber]);
            switch($geneOfChromosome){
                case 0:
                    $temp_teacher = getTeachers($this->semester);
                    $population[$chromosomeNumber][$geneOfChromosome] = intToString($temp_teacher[array_rand($temp_teacher)]);
                    break;
                case 1:
                    $temp_Subject = getSubjects($this->semester,(int)$population[$chromosomeNumber][0]);
                    $population[$chromosomeNumber][$geneOfChromosome] =intToString($temp_Subject[array_rand($temp_Subject)]);
                    break;
                case 2:
                    $population[$chromosomeNumber][$geneOfChromosome] =intToString($this->halls[array_rand($this->halls)]);
                    break;
                case 3:
                    $population[$chromosomeNumber][$geneOfChromosome] = $this->lush[array_rand($this->lush)];
                    break;
                case 4:
                    $population[$chromosomeNumber][$geneOfChromosome] = $this->groups[array_rand($this->groups)];
                    break;
                case 5:
                    $population[$chromosomeNumber][$geneOfChromosome] =$this->days[array_rand($this->days)];
                    break;
                case 6:
                    $count = 0;
                    $temp_time = null;
                    do{
                        $temp_time=$this->startTime[array_rand($this->startTime)];
                        $count++;
                    }while($temp_time > $population[$chromosomeNumber][$geneOfChromosome] && $count < 100);

                    $population[$chromosomeNumber][$geneOfChromosome]=$temp_time;

                    break;
                case 7:
                    $population[$chromosomeNumber][$geneOfChromosome]=$this->duration[array_rand($this->duration)];
            }
        }
        return $population;
    }

    public function toString($array)
     {
         $population_string=null;
         for ($i = 0; $i <  count($array); $i++) {
             $population_string.="[".$array[$i]."] ";
         }

         return $population_string;
     }
}

?>
