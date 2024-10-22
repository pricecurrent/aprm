<?php

Schedule::command('app:example-command') // schedule a task that has the signature of `app:example-command`
    ->withoutOverlapping() // in case when it's time to run the command again, make sure to not start the new command unless the previous one is still running
    ->hourly() // to run every hour
    ->onOneServer() // in case of the task scheduler running on multiple worker servers, make sure to run the command on one server only. The server that got to run the command first, will be running the subsequent commands, which is possible by securing the atomic lock on the job
    ->runInBackground(); // allow other commands that are scheduled at the same time to run, without waiting for this command to finish.
