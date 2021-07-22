<?php

namespace App\Http\Controllers\Invitation;

use Illuminate\Http\Request;
use App\Core\CrudController;
use App\Services\Invitation\InvitationService;
/** @property InvitationService $service */
class InvitationController extends CrudController
{
    public function __construct(InvitationService $service)
    {
        parent::__construct($service);
    }
}