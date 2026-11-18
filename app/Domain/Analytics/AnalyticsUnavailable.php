<?php

namespace App\Domain\Analytics;

use RuntimeException;

/** A report could not be loaded; the message is shown to the admin as is. */
class AnalyticsUnavailable extends RuntimeException {}
