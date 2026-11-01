<?php
namespace weburg\ghowst;

class HttpWebServiceHandler {
    private HttpWebServiceMapper $httpWebServiceMapper;

    public function __construct(HttpWebServiceMapper $httpWebServiceMapper, $caller) {
        $this->httpWebServiceMapper = $httpWebServiceMapper;
    }

    public function handleRequest(): void {
        if ($_SERVER['REQUEST_METHOD'] == HttpMethod::OPTIONS->name) {
            phpinfo(); // TODO
        }

        if ($_SERVER['REQUEST_METHOD'] == HttpMethod::GET->name) {
            // TODO char encoding to UTF-8

            if (isset($_GET['phpinfo'])) {
                phpinfo(); // TODO
            }

            if (isset($_GET['ahttpi'])) {
                echo "Future AHTTPI";
            }
        }
    }

    protected function doOptions(): void {
        header("access-control-allow-methods: " + ""); // TODO dynamicize
    }
}
?>