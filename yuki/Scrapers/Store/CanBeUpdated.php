<?php

namespace yuki\Scrapers\Store;

use yuki\Scrapers\Versioning;

trait CanBeUpdated
{
    protected string $packageName;

    protected int $versionCode;

    /**
     * Check if the package requires any updates.
     *
     * @return bool
     */
    public function canBeUpdated(): bool
    {
        return app(Versioning::class)->areUpdatesAvailableFor($this->packageName, $this->versionCode);
    }
}
