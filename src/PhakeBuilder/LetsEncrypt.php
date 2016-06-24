<?php
namespace PhakeBuilder;

/**
 * Lets Encrypt Helper Class
 *
 * This class helps with issuing of Let's Encrypt SSL certifications
 *
 * @author Leonid Mamchenkov <l.mamchenkov@qobo.biz>
 */
class LetsEncrypt extends BaseCommand
{

    /**
     * Certbot command string
     */
    protected $command = '/opt/letsencrypt/certbot-auto';

    /**
     * Issue certificate using standalone webroot
     *
     * @throws InvalidArgumentException
     * @param string $config Path to sami.php configuration to use
     * @return string
     */
    public function certonly($email, $webroot, $domains)
    {

        $result = $this->command . ' certonly --webroot --debug --agree-tos';
        $result .= ' --email ' . $email;
        $result .= ' -w ' . $webroot;

        foreach ($domains as $domain) {
            $result .= ' -d ' . $domain;
        }

        return $result;
    }
}
