<?php
    declare(strict_types = 1);

    class GrievanceBean
    {
        private $ticket_id;
        private $title, $dept_name, $status, $handler_name, $year, $description;
        
        public function setTicket_id($ticket_id)
        {
            $this->ticket_id = $ticket_id;
        }
        public function getTicket_id():int
        {
            return $this->ticket_id;
        }
        public function setTitle($title)
        {
            $this->title = $title;
        }
        public function getTitle():string
        {
            return $this->title;
        }
        public function setDept_name($dept_name)
        {
            $this->dept_name = $dept_name;
        }
        public function getDept_name():string
        {
            return $this->dept_name;
        }
        public function setStatus($status)
        {
            $this->status = $status;
        }
        public function getStatus():string
        {
            return $this->status;
        }
        public function setHandler_name($handler_name)
        {
            $this->handler_name = $handler_name;
            
        }
        public function getHandler_name()
        {
            return $this->handler_name;
        }
        public function setYear($year)
        {
            $this->year = $year;
            
        }
        public function getYear()
        {
            return $this->year;
        }
        public function setDescription($description)
        {
            $this->description = $description;
            
        }
        public function getDescription()
        {
            return $this->description;
        }
    }
?>