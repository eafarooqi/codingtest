<?php

namespace App\Command;

use Exception;
use App\Machine\CigaretteMachine;
use App\Machine\PurchaseTransaction;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CigaretteMachine
 * @package App\Command
 */
class PurchaseCigarettesCommand extends Command
{
    private bool $isMachineReady = false;

    /**
     * @return void
     */
    protected function configure()
    {
        $this->addArgument('packs', InputArgument::REQUIRED, "How many packs do you want to buy?");
        $this->addArgument('amount', InputArgument::REQUIRED, "The amount in euro.");
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return void
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): void
    {
        $quantity = (int) $input->getArgument('packs');
        $amount = (float) \str_replace(',', '.', $input->getArgument('amount'));

        // validating input values.
        if($validationMessage = $this->validateInput($quantity, $amount)){
            throw new Exception($validationMessage);
        }

        // executing machine
        $cigaretteMachine = new CigaretteMachine();
        $purchaseTransaction = new PurchaseTransaction($quantity, $amount);
        $purchasedItem = $cigaretteMachine->execute($purchaseTransaction);

        // printing out summary
        $message = 'You bought <info>%d</info> packs of cigarettes for <info>%01.2f</info>, each for <info>%01.2f</info>.';
        $output->writeln(sprintf(
            $message,
            $purchasedItem->getItemQuantity(),
            $purchasedItem->getTotalAmount(),
            CigaretteMachine::ITEM_PRICE,
        ));

        // printing out change
        $output->writeln('Your change is:');
        $purchasedItem->getChange() ? $this->renderCoinsChangeTable($output, $purchasedItem->getChange()) : $output->writeln('0');
    }

    /**
     * validate user input
     *
     * @param int $quantity
     * @param float $amount
     * @return bool|string
     */
    private function validateInput(int $quantity, float $amount): bool|string
    {
        // if quantity is lower than 1
        if($quantity < 1){
            return 'Invalid quantity: Quantity cannot be lower than one';
        }

        // if given amount is less than the total cost of packet(s)
        if($amount < ($quantity * CigaretteMachine::ITEM_PRICE)){
            return 'Invalid Amount: Amount not enough for purchase.';
        }

        return false;
    }

    /**
     * render coin change table
     *
     * @param $output
     * @param array $rows
     * @return void
     */
    private function renderCoinsChangeTable($output, array $rows): void
    {
        $table = new Table($output);
        $table->setHeaders(array('Coin(s)', 'Count'))->setRows($rows);
        $table->render();
    }
}