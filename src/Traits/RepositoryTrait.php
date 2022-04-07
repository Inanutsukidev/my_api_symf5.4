<?php
trait RepositoryTrait
{
    public function makeSettersQuery(array $datas): string
    {
        $set_str = "";
        $first = true;

        foreach ($datas as $colonne => $valeur) {
            if ($first) {
                $set_str =  $colonne . " = '$valeur'";
                $first = false;
            } else {

                $set_str .= "," . $colonne . " = '$valeur'";
            }
        }

        return $set_str;
    }
}
