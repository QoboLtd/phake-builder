<?php
/**
 * Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Qobo Ltd. (https://www.qobo.biz)
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
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
