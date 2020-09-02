<?php

namespace App\Controller;

use App\Models\User;
use App\Services\DocuSignService;
use Symfony\Component\HttpFoundation\Response;

class UserController
{
    private $docuSignService;
    private $user;

    /**
     * UserController constructor.
     * @param DocuSignService $docuSignService
     * @param User $user
     */
    public function __construct(DocuSignService $docuSignService, User $user)
    {
        $this->docuSignService = $docuSignService;
        $this->user = $user;
    }

    public function activateAccount(string $refId, string $uuid)
    {
        $user = $this->user->getCurrentUser();

        $response = $this->docuSignService->signDocument($refId, $uuid);

        if($response->status === 'verified')
        {
            $user->update(['verified'=>true]);
            return new Response('User has been verified');
        } else {
            return new Response('User cant be verified', 502);
        }
    }

}