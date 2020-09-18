<?php

namespace App\Console\Commands;

use App\TakeawayMailer\Message;
use App\TakeawayMailer\TakeawayMailer;
use Illuminate\Console\Command;

class MessageSend extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'message:send {subject} {body} {to} {reference_id=?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send email message';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        try
        {
            $message = Message::initFromCommandArguments($this->arguments());
            TakeawayMailer::queue($message);
            $this->info("Message added to queue");
        }
        catch(\Exception $e)
        {
            $this->error($e->getMessage());
            return -1;
        }
        
        return 0;
    }
}
