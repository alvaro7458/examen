<?php

namespace App\Http\Controllers\User;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class UserController extends ApiController
{
   public function index()
    {
        $user = User::all();
        return $this->mostrarTodo($user);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $reglas = [
            'name' => 'required',
            'email'=> 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        $this->validate($request, $reglas);

        $campos = $request->all();
        $campos ['password'] = bcrypt($request->password);
        $campos ['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos ['token_verificacion'] = User::generarVerificatioToken();
        $campos ['admin'] = User::USUARIO_REGULAR;

        $user = User::create($campos);

        return $this->mostrarUno($user,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        return $this->mostrarUno($user);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $reglas = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR,
        ];

        $this->validate($request, $reglas);

         if ($request->has('name')){
            $user->name = $request->name;
        }

        if($request->has('email') && $user->email != $request->email){
                $user->verified = User::USUARIO_NO_VERIFICADO;
                $user->token_verificacion = User::generarVerificatioToken();
                $user->email = $request->email;
        }

         if ($request->has('password')){
            $user->password = bcrypt($request->password);

         }

          if ($request->has('admin')){
            if(!$user->esVerificado()){
                return $this->errorResponse( 'Unicamente los usuarios verificados pueden cambiar su valor de administrador', 409);
            }
            $user->admin = $request->admin;
          }

       if(!$user->isDirty()){
            return $this->errorResponse( 'Se debe especificar al menos un valor diferente para actualizar', 422);
       }

        $user->save();

        return $this->mostrarUno($user);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        $user->delete();

        return $this->mostrarUno($user);
    }
}
