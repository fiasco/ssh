<?php

namespace Fiasco\Ssh;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Secure file transfer.
 */
class Ssh {
  use sshTrait;

  protected $host;

  public function setHost($host)
  {
    $this->host = $host;
    return $this;
  }

  public function getProcess($remote_cmd)
  {
    if (empty($this->host)) {
      throw new Exception("No host found to ssh too.");
    }
    $command = $this->prepareCommand();

    if (!empty($this->port)) {
      $command[] = '-p';
      $command[] = $this->port;
    }

    $command[] = $this->host;
    $command[] = $remote_cmd;

    return new Process($command);
  }

}
