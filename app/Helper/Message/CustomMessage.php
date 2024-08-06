<?php

namespace App\Helper\Message;

class CustomMessage
{
    /**
     * Message for system error
     */
    const notFoundSearch = "Failed ! There's no data match for your request";
    const emptyData = "Failed ! Data is empty";
    const internalServerError = "Failed ! There's some trouble on our system. Please try again, or contact our support";
    const errorDatabase = "Failed ! There's some trouble on our system. Please contact our support";

    /**
     * Message for client error
     */
    const unauthenticatedUser = "Failed on authenticate user. Please provide your credential";
    const unauthorizedUser = "Failed ! you are forbidden to access this section";

    const unknownLogin = "Email atau Password anda tidak terdaftar ! Silahkan coba lagi";

    /**
     * Message for client success
     */

    const successDelete = "Successfully delete data. Please wait you're being redirected";
    const successUpdate = "Successfully update data. Please wait you're being redirected";
    const successCreate = "Successfully create data. Please wait you're being redirected";
    const successGet = "Successfully get data";
}
