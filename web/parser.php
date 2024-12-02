<?php
    include_once 'tokenizer.php';

    class Parser{
        private $tokenizer = null;
        private $tokens;
        private $currLine;

        public function __construct(){
            $this->tokenizer = new Tokenizer();
        }

        public function setTokenizer($tokenizer){
            $this->tokenizer = $tokenizer;
        }

        public function compile($source){
            $this->currLine = 0;
            if(is_string($source)) {
                if(!$this->tokenizer) {
                    throw new \Exception("Tokenizer not found.");
                }
                $this->tokens = $this->tokenizer->tokenize($source);
            }
            $compiled = '';
            while(!empty($this->tokens)){
                $compiled .= $this->parseExpression();
            }
            return $compiled;
        }

        public function getFirstToken(){
            if(count($this->tokens) == 0) {
                throw new \Exception("Unexpected end of input at line 
                    $this->currLine .");
            }
    
            return $this->tokens[0];
        }

        public function parseExpression(){
            $token = $this->getFirstToken();
            $type_of_token = $token[0];

            switch($type_of_token){
                case 'IF_OPEN':
                    return $this->parseIf();
                case 'FOR_OPEN':
                    return $this->parseFor();
                case 'VALUE':
                    return $this->parseValue();
                case 'HTML':
                    return $this->parseHTML();
                default:
                    throw new Exception(
                        "Invalid token type: $type");
            }
        }

        public function parseHTML(){
            $html_token = $this->pop('HTML');
            $output = '$output .= \'' . $this->escapeSingleQuote($html_token[1]) . "';\n";
            return $output;
        }

        public function parseFor(){
            $for_open_token = $this->pop('FOR_OPEN');
            $output = '$index = 0; foreach(' . $for_open_token[1][1] . ' as ' . $for_open_token[1][0] . ') {'."\n";

            while(true){
                $tmp_token = $this->getFirstToken();
                if($tmp_token[0] == 'FOR_CLOSE'){
                    $this->pop('FOR_CLOSE');
                    $output .= '$index++; }' ."\n";
                    break;
                } else {
                    $output .= $this->parseExpression();
                }
            }
            
            return $output;
        }

        public function parseIf(){
            $token = $this->pop("IF_OPEN");
            $output = "if ($token[1]){\n";
            while(true){
                $tmp_token = $this->getFirstToken();
                if($tmp_token[0] == 'IF_CLOSE'){
                    $this->pop('IF_CLOSE');
                    $output .=  "}\n";
                    break;
                } else if($tmp_token[0] == 'ELSE_IF'){
                    $this->pop('ELSE_IF');
                    $output .= "} else if($tmp_token[1]) {\n";
                } else if($tmp_token[0] == 'ELSE'){
                    $this->pop('ELSE');
                    $output .= "} else {\n";
                } else {
                    $output .= $this->parseExpression();
                }
            }
            return $output;
        }

        public function parseValue(){
            $token = $this->pop('VALUE');
            return '$output .= ' . $token[1] . ";\n";
        }

        public function render($file, $context = array()){
            $file = 'pages/' . $file;
            if(!file_exists($file)){
                throw new \Exception("File $file not found");
            }

            $hash = md5($file . serialize($context));
            $cached_file = 'cache/' . "$hash.cache";
            if(file_exists($cached_file) && filemtime($cached_file) > filemtime($file)){ //rememberf
                include_once $cached_file;
                return;
            }

            $compiled_data = $this->compile(file_get_contents($file));
            ob_start();
            var_export($context);
            $stringified_context = ob_get_clean();
            $compiled_data = "<?php\n\t\$output = '';\n\$context = $stringified_context;\nextract(\$context);\n$compiled_data echo \$output;\n?>";
            file_put_contents($cached_file,$compiled_data);
            include_once $cached_file;
            return;
        }
        private function pop($expected = null) {
            $token = array_shift($this->tokens);

            if(!is_null($expected) && $token[0] != $expected) {
                throw new SyntaxErrorException("Invalid token: Got $token[0], expected $expected.");
            }
    
            return $token;
        }

        private function escapeSingleQuote($string){
            return str_replace("'","\\'",$string);
        }
    }
?>