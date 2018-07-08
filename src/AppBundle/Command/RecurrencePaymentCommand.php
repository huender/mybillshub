<?php
namespace AppBundle\Command;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RecurrencePaymentCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('app:recurrence-payment:active-services')
            ->setDescription('Update all recurrence payments status.')
        ;
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->getContainer()->get('app.recurrence_payment')->changeStatusOfRecurrencePayments();
        $output->writeln(sprintf(
            "%s: Recurring payments successfully updated.",
            date('d/m/Y H:i:s')
        ));
    }
}