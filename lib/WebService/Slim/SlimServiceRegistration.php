<?php

class SlimServiceRegistration
{
    /**
     * @var string
     */
    protected $route;
    /**
     * @var mixed
     */
    protected $callback;
    /**
     * @var SlimServiceMetadata
     */
    protected $metadata;
    /**
     * @var string
     */
    protected $routeName;

    public function __construct($categoryName, $route, $callback, $routeName)
    {
        $this->route = '/' . $this->trim($categoryName) . '/' . $this->trim($route);
        $this->callback = $callback;
        $this->metadata = new SlimServiceMetadata($callback);
        $this->routeName = $routeName;
    }

    private function trim($str)
    {
        $s = $str;
        $s = trim($s, '/');
        $s = trim($s, '\\');

        return $s;
    }

    /**
     * @return string
     */
    public function Route()
    {
        return $this->route;
    }

    /**
     * @return mixed
     */
    public function Callback()
    {
        return $this->callback;
    }

    /**
     * @return SlimServiceMetadata
     */
    public function Metadata()
    {
        return $this->metadata;
    }

    /**
     * @return string
     */
    public function RouteName()
    {
        return $this->routeName;
    }

    /**
     * @return bool
     */
    public function IsSecure()
    {
        return false;
    }

    /**
     * @return bool
     */
    public function IsLimitedToAdmin()
    {
        return false;
    }
}

class SlimSecureServiceRegistration extends SlimServiceRegistration
{
    public function IsSecure()
    {
        return true;
    }
}

class SlimAdminServiceRegistration extends SlimSecureServiceRegistration
{
    public function IsLimitedToAdmin()
    {
        return true;
    }
}

class SlimServiceMetadata
{
    public $description;
    public $return;
    public $request;
    public $name;

    public function __construct($callback)
    {
        if (is_object($callback[0])) {
            $class = new ReflectionClass(get_class($callback[0]));
        } else {
            $class = new ReflectionClass($callback[0]);
        }

        $method = $class->getMethod($callback[1]);
        $doc = $this->processPHPDoc($method);

        $this->name = isset($doc['name']) ? $doc['name'] : null;
        $this->description = isset($doc['description']) ? $doc['description'] : null;
        $this->return = $doc['response'];
        $this->request = isset($doc['request']) ? $doc['request'] : null;
    }

    /**
     * @return string
     */
    public function Name()
    {
        if (empty($this->name)) {
            return 'Missing Name';
        }
        return $this->name;
    }

    /**
     * @return string
     */
    public function Description()
    {
        if (empty($this->description)) {
            return 'Missing Description';
        }
        return $this->description;
    }

    /**
     * @return object|string|null
     */
    public function Response()
    {
        if (!is_null($this->return)) {
            $type = $this->return['type'];
            if (class_exists($type)) {
                if (method_exists($type, 'Example')) {
                    return $type::Example();
                }
                return new $type();
            } elseif ($type != 'void') {
                return $type;
            }
        }

        return null;
    }

    /**
     * @return object|string|null
     */
    public function Request()
    {
        if (!is_null($this->request)) {
            $type = $this->request['type'];
            if (class_exists($type)) {
                if (method_exists($type, 'Example')) {
                    return $type::Example();
                }
                return new $type();
            } else {
                return $type;
            }
        }

        return null;
    }

    private function processPHPDoc(ReflectionMethod $reflect)
    {
        // Credit: http://gonzalo123.com/2011/04/04/reflection-over-phpdoc-with-php/

        $phpDoc = ['params' => [], 'response' => null];
        $docComment = $reflect->getDocComment();
        if (trim($docComment) == '') {
            return null;
        }
        $docComment = preg_replace('#[ \t]*(?:\/\*\*|\*\/|\*)?[ ]{0,1}(.*)?#', '$1', $docComment);
        $docComment = ltrim($docComment, "\r\n");
        $parsedDocComment = $docComment;
        $lineNumber = $firstBlandLineEncountered = 0;
        while (($newlinePos = strpos($parsedDocComment, "\n")) !== false) {
            $lineNumber++;
            $line = substr($parsedDocComment, 0, $newlinePos);

            $matches = [];
            if ((strpos($line, '@') === 0) && (preg_match(
                '#^(@\w+.*?)(\n)(?:@|\r?\n|$)#s',
                $parsedDocComment,
                $matches
            ))
            ) {
                $tagDocblockLine = $matches[1];
                $matches2 = [];

                if (!preg_match('#^@(\w+)(\s|$)#', $tagDocblockLine, $matches2)) {
                    break;
                }
                $matches3 = [];
                if (!preg_match('#^@(\w+)\s+([\w|\\\]+)(?:\s+(\$\S+))?(?:\s+(.*))?#s', $tagDocblockLine, $matches3)) {
                    break;
                }
                if ($matches3[1] != 'param') {
                    $str = strtolower($matches3[1]);
                    if ($str == 'response') {
                        $phpDoc['response'] = ['type' => $matches3[2]];
                    } elseif (strtolower($matches3[1]) == 'request') {
                        $phpDoc['request'] = ['type' => $matches3[2]];
                    } elseif (strtolower($matches3[1]) == 'name') {
                        $phpDoc['name'] = $matches3[2];
                    } elseif (strtolower($matches3[1]) == 'description') {
                        $phpDoc['description'] = str_replace('@description ', '', $matches3[0]);
                    }
                } else {
                    $phpDoc['params'][] = ['name' => $matches3[3], 'type' => $matches3[2]];
                }

                $parsedDocComment = str_replace($matches[1] . $matches[2], '', $parsedDocComment);
            } else {
                // Not a tag, e.g. a summary line or a blank line between
                // tags. Skip it so that every pass shortens the comment;
                // without this the loop never ends.
                $parsedDocComment = substr($parsedDocComment, $newlinePos + 1);
            }
        }
        return $phpDoc;
    }
}
