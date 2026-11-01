<?php
namespace weburg\ghowst;

class HttpWebServiceMapper {
    private object $webService;
    private string $webServiceUriBasePath;
    private string $serviceDescription;
    private WebService $webServiceMetadata;

    public function __construct(object $webService, string $webServiceUriBasePath) {
        $this->webService = $webService;
        $this->webServiceUriBasePath = $webServiceUriBasePath;

        $this->serviceDescription = $this->describeService();

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