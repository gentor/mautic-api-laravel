<?php

namespace Gentor\Mautic\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Mautic
 *
 * @method static \Mautic\Api\Assets assets()
 * @method static \Mautic\Api\CampaignEvents campaignEvents()
 * @method static \Mautic\Api\Campaigns campaigns()
 * @method static \Mautic\Api\Categories categories()
 * @method static \Mautic\Api\Companies companies()
 * @method static \Mautic\Api\CompanyFields companyFields()
 * @method static \Mautic\Api\ContactFields contactFields()
 * @method static \Gentor\Mautic\Api\Contacts contacts()
 * @method static \Mautic\Api\Data data()
 * @method static \Mautic\Api\Devices devices()
 * @method static \Mautic\Api\DynamicContents dynamicContents()
 * @method static \Mautic\Api\Emails emails()
 * @method static \Mautic\Api\Files files()
 * @method static \Mautic\Api\Forms forms()
 * @method static \Gentor\Mautic\Api\Form form($formId)
 * @method static \Mautic\Api\Messages messages()
 * @method static \Mautic\Api\Notes notes()
 * @method static \Mautic\Api\Notifications notifications()
 * @method static \Mautic\Api\Pages pages()
 * @method static \Mautic\Api\Points points()
 * @method static \Mautic\Api\PointTriggers pointTriggers()
 * @method static \Mautic\Api\Reports reports()
 * @method static \Mautic\Api\Roles roles()
 * @method static \Mautic\Api\Segments segments()
 * @method static \Mautic\Api\Smses smses()
 * @method static \Mautic\Api\Stages stages()
 * @method static \Mautic\Api\Stats stats()
 * @method static \Mautic\Api\Users users()
 *
 * @package Gentor\Mautic\Facades
 * @see \Gentor\Mautic\MauticService
 */
class Mautic extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'mautic';
    }
}
