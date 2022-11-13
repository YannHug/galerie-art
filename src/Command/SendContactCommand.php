<?php

namespace App\Command;

use App\Repository\ContactRepository;
use App\Repository\UserRepository;
use App\Service\ContactService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class SendContactCommand extends Command
{
    protected static $defaultName = 'app:send-contact';
    private $contactRepository;
    private $mailer;
    private $contactService;
    private $userRepository;

    public function __construct(ContactRepository $contactRepository, MailerInterface $mailer, ContactService $contactService, UserRepository $userRepository)
    {
        $this->contactRepository = $contactRepository;
        $this->mailer = $mailer;
        $this->contactService = $contactService;
        $this->userRepository = $userRepository;
        parent::__construct();
    }

    /**
     * @throws NonUniqueResultException
     * @throws TransportExceptionInterface
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $toSend = $this->contactRepository->findBy(['isSend' => false]);
        $address = new Address(
            $this->userRepository->getPeintre()->getEmail(),
            $this->userRepository->getPeintre()->getNom() . ' ' .
            $this->userRepository->getPeintre()->getPrenom()
        );

        foreach ($toSend as $mail) {
            $email = (new Email())
                ->from($mail->getEmail())
                ->to($address)
                ->subject(('Nouveau message de ' . $mail->getNom()))
                ->text($mail->getMessage());

            $this->mailer->send($email);

            $this->contactService->isSend($mail);
        }

        return Command::SUCCESS;
    }
}