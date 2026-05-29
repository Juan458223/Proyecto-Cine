<?php
class SmtpConfig{
    private static $instance = null;
    private $settings;
    private function __construct() {
       $this->settings = [
            'smtp_host' => 'smtp.gmail.com',
            'smtp_user' => 'cine0first@gmail.com',
            'smtp_pass' => 'dguy tnrm jthr renc',
            'smtp_port' => 587
        ];
    }

     public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new SmtpConfig();
        }
        return self::$instance;
    }
    public function get($key) {
        return $this->settings[$key];
    }

}
 