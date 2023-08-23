<?php

namespace App\API\V1;

use App\API\APIController;

/** 
 * This class acts as a wrapper for the authentication,
 * validation, and response return streams from the main activity
 */

class APIV1Controller extends APIController
{

    private $file = [];
    private $payload = [];
    private $addPayload = [];
    private $payloadRules = [];
    private $mainActivity = null;

    // Default function if client unauthorized
    private function __unauthorizedScheme()
    {

        return $this->error(401);
    }

    /** Function to check payload
     * 
     * @return void
     */
    private function payloadChecker()
    {

        return $this->payloadHandler
            ->setValidationData(
                $this->payload,
                $this->file,
                $this->payloadRules
            )
            ->validationSuccess(function () {

                if (is_callable($this->mainActivity))
                    return call_user_func(
                        $this->mainActivity,
                        $this->payload,
                        $this->file,
                        $this->request->auth->data
                    );
            })
            ->validationFail(function ($err) {

                // Call default method when validation failed
                return $this->error(400, $err);
            })
            ->validate();
    }

    /** 
     * 
     * @return void
     */
    public function setup(array $payload, array $rules, $mainActivity)
    {

        $this->addPayload = $payload;
        $this->payloadRules = $rules;
        $this->mainActivity = $mainActivity;

        return $this->run();
    }

    /** Function to combine initial payload and addon payload
     * 
     * @param array $payload
     * @param array $addon
     * @param mixed $return
     * 
     * @return void
     */
    private static function combinePayload(
        $payload = [],
        $addon = [],
        &$return
    ) {

        foreach ($addon as $key => $val) {

            $payload[$key] = $val;
        }

        $return = $payload;
    }

    /** Root function to run
     * 
     * @return void
     */
    private function run()
    {

        $this->payload = $this->getPayload();
        $this->file = $this->getFile();

        // Combine payload
        self::combinePayload($this->payload, $this->addPayload, $this->payload);

        // Authorize client
        return $this->authHandler(
            fn () => $this->payloadChecker(),
            fn () => $this->__unauthorizedScheme()
        );
    }
}
