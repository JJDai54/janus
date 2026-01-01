    function getHelp(){
        return $this->_help;
    }
    function setHelp($value){
        $this->_help = $value;
    }
            
        if($this->_help){
            $html[] = "<br><span class = 'XoopsFormeEditList_help'>{$this->_help}</span>";
        }
