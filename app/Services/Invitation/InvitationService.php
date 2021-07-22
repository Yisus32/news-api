<?php
/**
 * Created by PhpStorm.
 * User: zippyttech
 */

namespace App\Services\Invitation;


use App\Core\CrudService;
use App\Repositories\Invitation\InvitationRepository;

/** @property InvitationRepository $repository */
class InvitationService extends CrudService
{

    protected $name = "invitation";
    protected $namePlural = "invitations";

    public function __construct(InvitationRepository $repository)
    {
        parent::__construct($repository);
    }

}