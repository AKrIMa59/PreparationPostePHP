<?php
require "lib/mysql.php";

class glpiQry extends mysql
{
    public function GetTicketInfo(int $ticketID)
    {
        $ticketObj = $this->queryObject("SELECT id,users_id_recipient,users_id_lastupdater,content,date_creation FROM glpi_tickets WHERE id = '". $ticketID ."' LIMIT 1");
        if (empty($ticketObj)) {
            return false;
        }
        return $ticketObj;
    }

    public function InsertTaskSimple($ticketObj, string $contentTask, int $duration, int $state = 1){
        $id = $this->insertQuery("INSERT INTO `glpi_tickettasks` (`date_creation`, `date_mod`, `tickets_id`, `date`, `users_id`, `content`, `actiontime`, `state`, `users_id_tech`) VALUES (CURRENT_TIME(), CURRENT_TIME(), '". $ticketObj->id ."', CURRENT_TIME(), '". $ticketObj->users_id_recipient ."', '". $contentTask ."', '". $duration ."', '". $state ."', '". $ticketObj->users_id_recipient ."')");
        return $id;
    }

    public function GetUsername(int $userID)
    {
        $userObj = $this->queryObject("SELECT name FROM glpi_users WHERE id = '". $userID ."' LIMIT 1");
        if (empty($userObj)) {
            return false;
        }
        return $userObj->name;
    }

    public function GetTicketTasks(int $ticketID)
    {
        $ticketTasks = $this->queryArray("SELECT id,users_id,users_id_tech,content,actiontime,state FROM glpi_tickettasks WHERE tickets_id = '". $ticketID ."'");
        if (empty($ticketTasks)) {
            return false;
        }
        return $ticketTasks;
    }
}
