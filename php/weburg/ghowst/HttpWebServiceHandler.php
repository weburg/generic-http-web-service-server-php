<?php
namespace weburg\ghowst;

require_once "weburg/ghowst/HttpWebServiceMapper.php";
require_once "weburg/ghowst/JsonNormalizer.php";

// This class is loosely analogous to the Java server's Jakarta Servlet (HttpWebServiceServlet)
class HttpWebServiceHandler {
    private HttpWebServiceMapper $httpWebServiceMapper;
    private string $webServiceUriBasePath;
    private ?object $caller;

    public function __construct(object $webService, string $webServiceUriBasePath, ?object $caller = null) {
        $this->httpWebServiceMapper = new HttpWebServiceMapper($webService, $webServiceUriBasePath);
        $this->webServiceUriBasePath = $webServiceUriBasePath; // TODO normalize base path, allow '/' but strip off training '/'
        $this->caller = $caller;
    }

    public function handleRequest(): void {
        if ($_SERVER['REQUEST_METHOD'] == HttpMethod::OPTIONS->name) {
            $this->handleOptions();
            return;
        }

        if ($_SERVER['REQUEST_METHOD'] == HttpMethod::GET->name) {
            // TODO char encoding to UTF-8, and also determine when to use callback based on accept header

            $httpPath = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
            if (str_starts_with($httpPath, $this->webServiceUriBasePath)) {
                $httpPath = substr($httpPath, strlen($this->webServiceUriBasePath));
            }

            if (empty($httpPath)) {
                if (isset($_GET['ahttpi'])) {
                    $this->handleDescribe();
                    return;
                }

            if (isset($_GET['phpinfo'])) {
                    phpinfo(); // TODO remove after development done
                    return;
                }

                $this->caller->handleGet();
                return;
            }
        }

        $handledResponse = $this->httpWebServiceMapper->handleInvocation($_SERVER["REQUEST_METHOD"], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), $_REQUEST);

        if ($handledResponse !== null) {
            echo json_encode(JsonNormalizer::normalize($handledResponse));
            }
        }

    protected function handleOptions(): void {
        header("access-control-allow-methods: " . ""); // TODO dynamicize
    }

    protected function handleDescribe(): void {
        echo "Future AHTTPI JSON"; // TODO
    }
}
?>