<?php

namespace App\Traits;

use App\Helper\Message\CustomMessage;

const debugMode = true;

trait ResponseFormatter
{

    public function message($message): string
    {
        if (debugMode) {
            return $message;
        } else {
            return CustomMessage::internalServerError;
        }
    }

    private function checkResponseCode(int $code): int
    {
        if ($code < 100 || $code > 599) {
            return 500;
        } else {
            return $code;
        }
    }

    public function httpResponse(int $code, string $message, $data = null): \Illuminate\Http\JsonResponse
    {
        $code = $this->checkResponseCode($code);
        if ($code == 500) {
            return response()->json([
                'code' => 500,
                'message' => $this->message($message),
                'data' => $data
            ], 500);
        } else {
            return response()->json([
                'code' => $code,
                'message' => $this->message($message),
                'data' => $data
            ], $code);
        }
    }

    public function arrayResponse(int $code, string $message, $data = null): array
    {
        $code = $this->checkResponseCode($code);
        if ($code == 500) {
            return array(
                'code' => 500,
                'message' => $this->message($message),
                'data' => $data
            );
        } else {
            return array(
                'code' => $code,
                'message' => $this->message($message),
                'data' => $data
            );
        }
    }
    public function arrayResponseLogin(int $code, string $message, $token = null): array
    {
        $code = $this->checkResponseCode($code);
        if ($code == 500) {
            return array(
                'code' => 500,
                'message' => $this->message($message),
                'token' => $token
            );
        } else {
            return array(
                'code' => $code,
                'message' => $this->message($message),
                'token' => $token
            );
        }
    }
    public function functionResponse(bool $status, int $code, string $message, $data = null): array
    {
        $code = $this->checkResponseCode($code);
        if ($code == 500) {
            return array(
                'status' => false,
                'code' => 500,
                'message' => $this->message($message),
                'data' => $data
            );
        } else {
            return array(
                'status' => $status,
                'code' => $code,
                'message' => $this->message($message),
                'data' => $data
            );
        }
    }

    public function jsonResponse(int $code, string $message, $data = null): string
    {
        $code = $this->checkResponseCode($code);
        if ($code == 500) {
            $response = array(
                'code' => 500,
                'message' => $this->message($message),
                'data' => $data
            );
        } else {
            $response = array(
                'code' => $code,
                'message' => $this->message($message),
                'data' => $data
            );
        }
        return json_encode($response);
    }
}
