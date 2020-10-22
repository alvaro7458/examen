<?php

namespace App\Transformers;

use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    /**
     * List of resources to automatically include
     *
     * @var array
     */
    protected $defaultIncludes = [
        //
    ];
    
    /**
     * List of resources possible to include
     *
     * @var array
     */
    protected $availableIncludes = [
        //
    ];
    
    /**
     * A Fractal transformer.
     *
     * @return array
     */
    public function transform(User $user)
    {
        return [

            'identificador' => (int)$user->id,
            'nombre' => (string)$user->name,
            'correo' => (string)$user->email,
            'fechaCorreo' => (string)$user->email_verified_at,
            'esVerificado' => (int)$user->verified,
            'esAdministrador' => ($user->admin === 'true'),
            'fechaCreacion' => (string)$user->created_at,
            'fechaActualizacion' => (string)$user->update_at,
            'fechaEliminacion' => isset( $user->deleted_at) ? (string) $user->deleted_at : null,

            'link' => [
                [
                    'rel' => 'self',
                    'href' => route('users.show', $user->id),
                ],
            ],

        ];
    }
}
