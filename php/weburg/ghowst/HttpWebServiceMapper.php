<?php
namespace weburg\ghowst;

use http\Exception\InvalidArgumentException;
use ReflectionMethod;

class HttpWebServiceMapper {
    private object $webService;
    private string $webServiceClass;
    private string $webServiceUriBasePath;
    private WebService $webServiceMetadata;

    private string $serviceDescription;

    public function __construct(object $webService, string $webServiceUriBasePath) {
        $this->webService = $webService;
        $this->webServiceClass = array_values(class_implements($webService))[0];
        $this->webServiceUriBasePath = $webServiceUriBasePath;
        $this->serviceDescription = $this->describeService();
    }

    public static function getResourceFromPath(string $pathInfo): string {
        $pathParts = explode('/', $pathInfo);
        if ($pathParts && count($pathParts) > 1) {
            return $pathParts[1];
        } else {
            return "";
        }
    }

    /*
    public static String getResourceFromMethod(String methodName) {
        if (methodName.startsWith("get")) {
            methodName = methodName.substring("get".length());
            return methodName.substring(0, 1).toLowerCase() + methodName.substring(1);
        } else if (methodName.startsWith("createOrReplace")) {
            methodName = methodName.substring("createOrReplace".length());
            return methodName.substring(0, 1).toLowerCase() + methodName.substring(1);
        } else if (methodName.startsWith("create")) {
            methodName = methodName.substring("create".length());
            return methodName.substring(0, 1).toLowerCase() + methodName.substring(1);
        } else if (methodName.startsWith("update")) {
            methodName = methodName.substring("update".length());
            return methodName.substring(0, 1).toLowerCase() + methodName.substring(1);
        } else if (methodName.startsWith("delete")) {
            methodName = methodName.substring("delete".length());
            return methodName.substring(0, 1).toLowerCase() + methodName.substring(1);
        } else {
            // Verb must be all lowercase, lowercase only first letter of resource name

            String[] parts = methodName.split("(?=[A-Z])");

                String resource = "";

                for (int i = 0; i < parts.length; i++) {
                if (i == 1) {
                    resource += parts[i].toLowerCase();
                } else if (i > 1) {
                    resource += parts[i];
                }
            }

            return resource;
        }
    }
    */

    public static function getCustomVerbFromPath(string $pathInfo): string {
        $pathParts = explode('/', $pathInfo);
        if (count($pathParts) > 2) {
            return $pathParts[count($pathParts) - 1];
        } else {
            return "";
        }
    }

    /*
    public static String getCustomVerbFromMethod(String methodName) {
        if (methodName.startsWith("get")) {
            return "";
        } else if (methodName.startsWith("createOrReplace")) {
            return "";
        } else if (methodName.startsWith("create")) {
            return "";
        } else if (methodName.startsWith("update")) {
            return "";
        } else if (methodName.startsWith("delete")) {
            return "";
        } else {
            // Verb must be all lowercase

            String[] parts = methodName.split("(?=[A-Z])");

                String verb = "";

                for (int i = 0; i < parts.length; i++) {
                verb += parts[i].toLowerCase();
                break;
            }

                return verb;
            }
    }
    */

    /*
    public static HttpMethod getHttpMethodFromServiceMethodName(String methodName) {
        if (methodName.startsWith("get")) {
            return HttpMethod.GET;
        } else if (methodName.startsWith("createOrReplace")) {
            return HttpMethod.PUT;
        } else if (methodName.startsWith("create")) {
            return HttpMethod.POST;
        } else if (methodName.startsWith("update")) {
            return HttpMethod.PATCH;
        } else if (methodName.startsWith("delete")) {
            return HttpMethod.DELETE;
        } else {
            return HttpMethod.POST;
        }
    }
    */

    public function handleInvocation(string $httpMethod, string $httpPath, array $httpArguments): mixed {
        if (str_starts_with($httpPath, $this->webServiceUriBasePath)) {
            $httpPath = substr($httpPath, strlen($this->webServiceUriBasePath));
        }

        $verb = "none";

        $resource = self::getResourceFromPath($httpPath);
        $customVerb = self::getCustomVerbFromPath($httpPath);

        if ($httpMethod == "GET") {
            $verb = "get";
        } else if ($httpMethod == "PUT") {
            $verb = "createOrReplace";
        } else if ($httpMethod == "POST") {
            if (strlen($customVerb) == 0) {
                $verb = "create";
            } else {
                $verb = $customVerb;
            }
        } else if ($httpMethod == "PATCH") {
            $verb = "update";
        } else if ($httpMethod == "DELETE") {
            $verb = "delete";
        } else {
            throw new InvalidArgumentException("The method " . $httpMethod . " was not in the expected format.");
        }

        if ($customVerb != "" && $httpMethod != "POST") {
            throw new InvalidArgumentException("The method " . $httpMethod . " cannot be used with a subresource (" . $customVerb . ").");
        }

        $methodName = $verb . ucfirst($resource);

        $ref = new ReflectionMethod($this->webService, $methodName);
        $ordered = [];

        // Build a lookup table for parameter names
        // TODO should come from describeService data
        $paramNames = [];
        foreach ($ref->getParameters() as $p) {
            $paramNames[$p->getName()] = $p;
        }

        // Reject unknown parameters
        foreach ($httpArguments as $key => $_) {
            if (!isset($paramNames[$key])) {
                throw new InvalidArgumentException("Unknown parameter: $key");
            }
        }

        // Build ordered + validated argument list
        foreach ($ref->getParameters() as $param) {
            $name = $param->getName();

            if (array_key_exists($name, $httpArguments)) {
                // Provided by client
                $ordered[$name] = $httpArguments[$name];
                continue;
            }

            if ($param->isDefaultValueAvailable()) {
                // Let PHP apply the default by not including it
                continue;
            }

            if ($param->allowsNull()) {
                // Optional nullable parameter
                $ordered[$name] = null;
                continue;
            }

            throw new Exception("Missing required parameter: $name");
        }

        $result = $this->webService->$methodName(...$ordered);

        return $result;
    }

    private function describeService(): string {
        $this->webServiceMetadata = new WebService();

        return "";
    }
}

namespace weburg\ghowst;

enum HttpMethod {
    case GET;
    case POST;
    case PUT;
    case PATCH;
    case DELETE;
    case OPTIONS;
}

class WebService {
    public string $name;
    public string $description;
    public string $uriBasePath;
    public array $serviceMethods;
    public array $customTypes;
    public array $resources;
}

class ServiceMethod {
    public string $name;
    public string $description;
    public array $parameters;
    public \weburg\httpwebservicemapper\MethodReturn $returns;
    public string $uriPath;
    public \weburg\httpwebservicemapper\HttpMethod $httpMethod;
}

class CustomType {
    public string $name;
    public string $description;
    public array $properties;
}

class Property {
    public string $name;
    public string $type;
    public string $description;
}

class Resource {
    public string $name;
    public string $uriPath;
    public array $allowMethods;
}

class MethodParameter {
    public string $name;
    public string $type;
    public string $description;
}

class MethodReturn {
    public string $type;
    public string $description;
}