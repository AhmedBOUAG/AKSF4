<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpFoundation\Request;

final class LocaleListener {

    public function onKernelRequest(RequestEvent $event) {
        $request = $event->getRequest();
        // some logic to determine the $locale
        $request->setLocale("ar");
    }

}
