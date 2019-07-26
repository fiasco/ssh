<?php

namespace Fiasco\Ssh;

use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

/**
 * Secure file transfer.
 */
class Scp {
  use sshTrait;

  protected $program, $source, $target, $targetDir, $targetLocation;

  static public function create($source, $target, $targetDir = NULL)
  {
    return (new static())
      ->setSource($source)
      ->setTarget($target)
      ->setTargetDir($targetDir);
  }

  public function setProgram($program)
  {
    $this->program = $program;
    return $this;
  }

  public function setSource($source)
  {
    $this->source = $source;
    return $this;
  }

  public function setTarget($target)
  {
    $this->target = $target;
    return $this;
  }

  public function setTargetDir($targetDir = NULL)
  {
    if (!empty($targetDir) && substr($targetDir, -1) != '/') {
      throw new Exception("targetDir must contain trailing slash: $targetDir.");
    }
    $this->targetDir = $targetDir;
    return $this;
  }

  public function getTargetLocation()
  {
    if (is_file($this->source)) {
      return $this->targetLocation . $this->source;
    }
    return $this->targetLocation;
  }

  public function compile()
  {
    if (empty($this->source)) {
      throw new Exception("No source provided.");
    }
    if (empty($this->target)) {
      throw new Exception("No target provided.");
    }

    $this->targetLocation = $this->target . ':';
    if (!empty($this->targetDir)) {
      $this->targetLocation .= $this->targetDir;
    }

    $command = $this->prepareCommand('scp');

    if (!empty($this->port)) {
      $command[] = '-P';
      $command[] = $this->port;
    }

    if (!empty($this->program)) {
      $command[] = '-S';
      $command[] = $this->program;
    }

    $command[] = $this->source;
    $command[] = $this->targetLocation;

    return new Process($command);
  }
}
