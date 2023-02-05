<?php

namespace Nieruchomosci\Model;

use Laminas\Mail\Message;
use Laminas\Mail\Transport\Smtp as SmtpTransport;
use Laminas\Mail\Transport\SmtpOptions;
use Laminas\Mime\Message as MimeMessage;
use Laminas\Mime\Part as MimePart;
use Laminas\Mime\Mime;
use Nieruchomosci\Model\Oferta;

class Zapytanie
{
    private array $smtpTransportConfig;

    private array $from;

    private string $to;

    public function __construct(array $config)
    {
        $this->from = $config['from'];
        $this->to = $config['to'];
        unset($config['from']);
        unset($config['to']);

        $this->smtpTransportConfig = $config;
    }

    /**
     * Wysya maila z zapytaniem ofertowym.
     *
     * @param array  $daneOferty
     * @param string $tresc
     * @return bool
     */
    public function wyslij($daneOferty, string $email_odbiorca, string $tresc, string $email_nadawca, string $telefon, $plik) : bool
    {
        $transport = new SmtpTransport();
        $options = new SmtpOptions($this->smtpTransportConfig);
        $transport->setOptions($options);

        $part1 = new MimePart("Klient wyraził zainteresowanie ofertą numer *$daneOferty[numer]* o treści:\n\n$tresc");
        $part1->type = 'text/plain';
        $part1->charset = 'utf-8';

        $part2 = new MimePart("\nAdres email kontaktowy klienta: $email_nadawca");
        $part2->type = 'text/plain';
        $part2->charset = 'utf-8';

        $part3 = new MimePart("\nTelefon klienta: $telefon");
        $part3->type = 'text/plain';
        $part3->charset = 'utf-8';

        $part4 = new MimePart($plik);
        $part4->type = 'application/pdf';
        $part4->filename = "\noferta_$daneOferty[numer].pdf";
        $part4->disposition = Mime::DISPOSITION_ATTACHMENT;
        $part4->encoding = Mime::ENCODING_BASE64;

        $body = new MimeMessage();
        $body->setParts([$part1, $part2, $part3, $part4]);

        $message = new Message();
        $message->setEncoding('UTF-8');
        $message->setFrom($this->from['email'], $this->from['name']); // konto do wysyłania maili z serwisu
        $message->addTo($email_odbiorca, "Administrator"); // osoba obsługująca zgłoszenia
        $message->setSubject("Zainteresowanie ofertą");
        $message->setBody($body);

        try {
            $transport->send($message);
            return true;
        } catch (\Exception $e) {
            echo $e->getMessage();

            return false;
        }
    }
}
