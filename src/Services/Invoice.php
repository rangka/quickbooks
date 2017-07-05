<?php

namespace ReneDeKat\Quickbooks\Services;

use Psr\Http\Message\StreamInterface;
use ReneDeKat\Quickbooks\Services\Traits\Attachable;
use ReneDeKat\Quickbooks\Services\Traits\Itemizable;

class Invoice extends Service
{
    use Itemizable, Attachable;

    /**
     * Download Invoice as PDF.
     *
     * @param string $id Invoice ID.
     *
     * @return StreamInterface
     */
    public function downloadPdf($id)
    {
        return $this->request('GET', $this->getResourceName().'/'.$id.'/pdf', [], [
            'Accept' => 'application/pdf',
        ]);
    }

    /**
     * Send an invoice through email.
     *
     * @param string $id    Invoice ID.
     * @param string $email Email to be sent to.
     *
     * @return StreamInterface
     */
    public function send($id, $email = null)
    {
        $url = $this->getResourceName().'/'.$id.'/send';

        if ($email) {
            $url .= '?sendTo='.urlencode($email);
        }

        return $this->request('POST', $url, [], [
            'Content-Type' => 'application/octet-stream',
        ]);
    }
}
