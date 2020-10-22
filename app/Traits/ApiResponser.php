<?php 


namespace App\Traits;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;



trait ApiResponser
{
protected function successResponse($data, $code)
	{
		return response()->json($data, $code);
	}

	protected function errorResponse($message, $code)
	{
		return response()->json(['error' => $message, 'code' => $code], $code);
	}

	protected function mostrarTodo(Collection $collection, $code = 200)
	{
		return $this->successResponse(['data' => $collection], $code);
	}

	protected function mostrarUno(Model $instance, $code = 200)
	{
		return $this->successResponse(['data' => $instance], $code);
	}

	protected function mostrarMensaje($message, $code = 200)
	{
		return $this->successResponse(['data' => $message], $code);
	}

}