<?php
    class Tokenizer{
        private $token_matcher;

        public function __construct(){
            $this->token_matcher = array(
                [
                    'VALUE',
                    '/^{{\s*(.*?)\s*}}$/'
                ],
                [
                    'FOR_OPEN',
                    '/^[\s\t]*@for\s+(.+?)\s+in\s+(.+?)\n$/'
                ],
                [
                    'FOR_CLOSE',
                    '/^[\s\t]*@endfor\s*\n$/'
                ],
                [
                    'IF_OPEN',
                    '/^[\s\t]*@if\s*(.+?)\s*\n$/'
                ],
                [
                    'ELSE',
                    '/^[\s\t]*@else\s*\n$/'
                ],
                [
                    'ELSE_IF',
                    '/^[\s\t]*@else\s+if\s*(.+?)\s*\n$/'
                ],
                [
                    'IF_CLOSE',
                    '/^[\s\t]*@endif\s*\n$/'
                ]
            );
        }

        public function tokenize($source){
            $token_content = '';
            $tokens = [];
            $html = '';
            foreach(str_split($source) as $character){
                if($character != '@' && $character != '{'){
                    if(empty($token_content)){
                        $html .= $character;
                        continue;
                    } else {
                        $token_content .= $character;
                    }
                } else {
                    if($character == '@'){
                        $tmp_lines = explode("\n",$html);
                        $last_line = trim(end($tmp_lines));
                        if(!empty($last_line)){
                            $html .= $character; 
                            continue;
                        } else {
                            array_push($tokens,['HTML',$html]);
                            $html = '';
                            $token_content .= $character;
                        }
                    } else {
                        if(substr($html,-1) != '{'){
                            $html .= $character;
                        } else {
                            $html = substr($html,0,-1);
                            array_push($tokens,['HTML',$html]);
                            $html = '';
                            $token_content = '{' . $character;
                        }
                    }
                }
                if(!empty($token_content)){
                    foreach($this->token_matcher as $matcher){
                        $value = $this->match($matcher,$token_content);
                        if(!is_null($value)){
                            array_push($tokens,[$matcher[0],$value]);
                            $token_content = '';
                        }
                    }
                }
                
            }
            if(!empty($html)){
                array_push($tokens,['HTML',$html]);
            }
            return $tokens;
        }

        public function match($matcher,$token_content){
            $match = null;
            preg_match($matcher[1],$token_content,$match,PREG_OFFSET_CAPTURE);
            $result = null;
            if(count($match) > 0){
                switch($matcher[0]){
                    case 'IF_OPEN':
                    case 'ELSE_IF':
                    case 'VALUE':
                        $result = $match[1][0];
                        break;
                    case 'FOR_CLOSE':
                    case 'ELSE':
                    case 'IF_CLOSE':
                        $result = '';
                        break;
                    case 'FOR_OPEN':
                        $result = [$match[1][0],$match[2][0]];
                        break;
                }
            }
            return $result;
        }
    }
?>