<?php
/*******************************************************************************
 * Name: Notification pusher / Wns adapter
 * Version: 1.0
 * Author: Przemyslaw Ankowski (przemyslaw.ankowski@gmail.com)
 ******************************************************************************/


// Default namespace
namespace Sly\NotificationPusher\Adapter;

use Sly\NotificationPusher\Model\BaseOptionedModel;
use Sly\NotificationPusher\Model\PushInterface;
use Sly\NotificationPusher\Collection\DeviceCollection;
use Sly\NotificationPusher\Exception\PushException;
use Sly\NotificationPusher\Client;
use Sly\NotificationPusher\Exception;
/**
 * Class Wns
 *
 * @package Sly\NotificationPusher\Adapter
 */
class Wns extends BaseAdapter 
{
    /**
     * Push
     *
     * @param \Sly\NotificationPusher\Model\PushInterface $Push Push
     * @return \Sly\NotificationPusher\Collection\DeviceCollection
     * @throws \Sly\NotificationPusher\PushException
     */
    public function push(PushInterface $Push) {
        // Create microsoft client
        $Client = new Microsoft();

        // Create pushed devices collection
        $PushedDevices = new DeviceCollection();

        // Try to send to each client
        foreach ($Push->getDevices() as $Device) {
            // Get message
            $Message = $Push->getMessage();

            // Try to send
            try {
                $this->response = $Client->send($Device->getToken(), 
                    $Message->getText(), 
                    $Message->getOption("view"), 
                    $Message->getOption("custom"));
            }

            // Something goes wrong
            catch (RuntimeException $Exception) {
                throw new PushException($Exception->getMessage());
            }

            // Add device to pushed devices list
            $PushedDevices->add($Device);
        }

        // Return pushed devices list
        return $PushedDevices;
    }

    /**
     * Supports
     *
     * @param string $token Token
     * @return boolean
     */
    public function supports($token) {
        return \strlen($token) > 0;
    }

    /**
     * Get default parameters
     *
     * @return array
     */
    public function getDefaultParameters() {
        return array();
    }
    /**
     * Get defined parameters
     *
     * @return array
     */
    public function getDefinedParameters(){
        return array();
    }
    /**
     * Get required parameters
     *
     * @return array
     */
    public function getRequiredParameters() {
        return array();
    }
}
