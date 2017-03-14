<?php

namespace Rangka\Quickbooks\Services;

use Rangka\Quickbooks\Builders\InvoiceItem;
use Rangka\Quickbooks\Client;
use Rangka\Quickbooks\Services\Traits\Itemizable;

class Invoice extends Service {
    use Itemizable;

    /**
     * Download Invoice as PDF
     *
     * @param string $id Invoice ID.
     * 
     * @return \GuzzleHttp\Psr7\Stream
     */
    public function downloadPdf($id) {
        return $this->request('GET', $this->getResourceName() . '/' . $id . '/pdf', [], [
            'Accept' => 'application/pdf'
        ]);
    }

    /**
     * Send an invoice through email.
     *
     * @param string $id    Invoice ID.
     * @param string $email Email to be sent to.
     * 
     * @return \GuzzleHttp\Psr7\Stream
     */
    public function send($id, $email = null) {
        $url = $this->getResourceName() . '/' . $id . '/send';

        if ($email) {
            $url .= '?sendTo=' . urlencode($email);
        }

        return $this->request('POST', $url, [], [
            'Content-Type' => 'application/octet-stream'
        ]);
    }
}