<?php

    class policeChase extends module {
        
        public $pageName = 'Police Chase';
        public $allowedMethods = array('move'=>array('type'=>'get'));
        
        public function constructModule() {
            
            $this->html .= $this->page->buildElement('policeChase', array());
            
        }
        
        public function method_move() {
            
            if (!$this->user->checkTimer('chase')) {
                $time = $this->user->getTimer('chase') - time();
                $crimeError = array('You cant commit another police chase untill your timer is up! (<span data-timer-type="inline" data-timer="'.($this->user->getTimer("chase") - time()).'"></span>)');
                $this->html .= $this->page->buildElement('error', $crimeError);
                
            } else {
                
                $rand = mt_rand(1, 4);
                
                if ($rand == 1) {
                
                    $winnings = mt_rand(150, 850) * $this->user->info->US_rank;
                    
                    $u = $this->db->prepare("UPDATE userStats SET US_money = US_money + $winnings WHERE US_id = ".$this->user->id);
                    $u->execute();
					
					$this->user->updateTimer('chase', 300, true);
                    
                    $this->html .= $this->page->buildElement('success', array('You got away, you were paid $'.number_format($winnings).'!'));
                    
                } else if ($rand == 3) {
                					
					$this->user->updateTimer('jail', 150, true);
					$this->user->updateTimer('chase', 300, true);
                    
                    $this->html .= $this->page->buildElement('error', array('You crashed and was sent to jail!'));
                    
                } else {
                    
                    $this->html .= $this->page->buildElement('info', array('You are still going, what dirrection do you want to go now?'));
                
                }
                
            }
            
        }
        
    }

?>