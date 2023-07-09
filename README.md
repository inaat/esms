 if ($fee_transaction[$key]==null) {
                    $total[$key]=null;

                } else {
                    $total[$key]=$fee_transaction[$key]+$bF[$key];
                }
                if ($total[$key]==null) {
                    $balance[$key]=$p -$discount_payment_formatted[$key];

                } else {
                    $balance[$key]=$total[$key]-$p -$discount_payment_formatted[$key];
                }