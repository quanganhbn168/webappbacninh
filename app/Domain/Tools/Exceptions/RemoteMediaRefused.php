<?php

namespace App\Domain\Tools\Exceptions;

use RuntimeException;

/** A cover-tool download that was refused or failed; the message is shown to the visitor. */
final class RemoteMediaRefused extends RuntimeException {}
