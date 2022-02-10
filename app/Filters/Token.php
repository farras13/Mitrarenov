<?php

namespace App\Filters;

use App\Models\AuthTokenModel;
use CodeIgniter\Config\Services;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class Token implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     * By default it should not return anything during
     * normal execution. However, when an abnormal state
     * is found, it should return an instance of
     * CodeIgniter\HTTP\Response. If it does, script
     * execution will end and that Response will be
     * sent back to the client, allowing for error pages,
     * redirects, etc.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        $mtoken = new AuthTokenModel();
        $headers = Services::request()->headers();

        try {
            $token = $headers['X-Auth-Token']->getValue();
        } catch (\Throwable $th) {
            return Services::response()->setJSON([
                "status" => ResponseInterface::HTTP_UNAUTHORIZED,
                "message" => "Token required",
                "data" => null
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }

        $cek = $mtoken->where('token', $token)->first();

        if (!$cek) {
            return Services::response()->setJSON([
                "status" => ResponseInterface::HTTP_UNAUTHORIZED,
                "message" => "Token Invalid !",
                "data" => null
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}
