<?php require_once('inc/loader.php'); ?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?=core_output_head()?>
    </head>
    <body>
        <div class="container">
            <div class="col-md-12">
                <h1>MIN&Xi;</h1>
            </div>
            <?php if ( $stat['waiting'] == '1' ) {
            echo '<div class="col-md-12"><p align="center"><em>There is insufficient data to produce any useful metrics.<br>Please check your wallet settings in config.php.<br>The pool you are querying may also be limiting API requests - please try later.</em></p></div>';
            die;
            } ?>
            <div class="col-md-6">
                <ul class="list-group">
                    <li class="list-group-item list-group-item-<?=$conf['colour']?>">
                        <h4>Statistics<span class="pull-right">ETH:<?=$fiat['code'].'['.number_format($ethtofiat,2)?>]</span></h4></li>
                        <?php $miner->get_statistics(); ?>
                    </ul>
                </div>
                <?php  ?>
                <div class="col-md-6">
                    <ul class="list-group">
                        <li class="list-group-item list-group-item-<?=$conf['colour']?>">
                            <h4>Progr&Xi;ss <span class="pull-right">&Xi; <?=$miner->get_balance();?></span></h4></li>
                            <li class="list-group-item">Remaining <span class="pull-right">&Xi; <?=$miner->get_remaining();?></span></li>
                            <li class="list-group-item">Time Left <span class="pull-right"><?=core_calc_remaining($miner->timeleft())?></span></li>
                            <li class="list-group-item">Next Payout <span class="pull-right"><?=$miner->nextpayout()?></span></li>
                        </ul>
                    </div>
                    <?php if ( $conf['show_bar'] == '1' ) { ?>
                    <div class="col-md-12">
                        <div class="progress">
                            <div class="progress-bar progress-bar-striped progress-bar-<?=$conf['colour']?> active" role="progressbar" aria-valuenow="<?=$stat['unpaid']?>" aria-valuemin="0" aria-valuemax="100" style="width:<?=$miner->get_progress();?>%">
                                <p>
                                <?=$miner->get_progress();?>%</p>
                            </div>
                        </div>
                        <br>
                    </div>
                    <?php } ?>
                    <!--         <div class="col-md-3">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-<?=$conf['colour']?>">
                            <h4>&Xi;TH &raquo; <?=$fiat['code']?></h4></li>
                            <?php if ( $conf['show_min'] == '1' ) { ?>
                            <li class="list-group-item">Minute <span class="pull-right"><?=$fiat['sym'].number_format(($stat['emin']*$ethtofiat),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_hour'] == '1' ) { ?>
                            <li class="list-group-item">Hour <span class="pull-right"><?=$fiat['sym'].number_format(($stat['ehour']*$ethtofiat),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_day'] == '1' ) { ?>
                            <li class="list-group-item">Day <span class="pull-right"><?=$fiat['sym'].number_format(($stat['eday']*$ethtofiat),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_week'] == '1' ) { ?>
                            <li class="list-group-item">Week <span class="pull-right"><?=$fiat['sym'].number_format(($stat['eweek']*$ethtofiat),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_month'] == '1' ) { ?>
                            <li class="list-group-item">Month <span class="pull-right"><?=$fiat['sym'].number_format(($stat['emonth']*$ethtofiat),2)?></span></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-<?=$conf['colour']?>">
                            <h4>฿TC &raquo; <?=$fiat['code']?></h4></li>
                            <?php if ( $conf['show_min'] == '1' ) { ?>
                            <li class="list-group-item">Minute <span class="pull-right"><?=$fiat['sym'].number_format(($stat['bmin']*$btctofiat),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_hour'] == '1' ) { ?>
                            <li class="list-group-item">Hour <span class="pull-right"><?=$fiat['sym'].number_format(($stat['bhour']*$btctofiat),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_day'] == '1' ) { ?>
                            <li class="list-group-item">Day <span class="pull-right"><?=$fiat['sym'].number_format(($stat['bday']*$btctofiat),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_week'] == '1' ) { ?>
                            <li class="list-group-item">Week <span class="pull-right"><?=$fiat['sym'].number_format(($stat['bweek']*$btctofiat),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_month'] == '1' ) { ?>
                            <li class="list-group-item">Month <span class="pull-right"><?=$fiat['sym'].number_format(($stat['bmonth']*$btctofiat),2)?></span></li>
                            <?php } ?>
                        </ul>
                    </div> -->
                    <!--  <?php if ( $conf['show_power'] == '1' ) { ?>
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-<?=$conf['colour']?>">
                            <h4>&Xi;TH Profitability</h4></li>
                            <?php if ( $conf['show_min'] == '1' ) { ?>
                            <li class="list-group-item">Minute<span class="pull-right"><?=$fiat['sym'].number_format((($stat['emin']*$ethtofiat)-$stat['power-min']),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_hour'] == '1' ) { ?>
                            <li class="list-group-item">Hour<span class="pull-right"><?=$fiat['sym'].number_format((($stat['ehour']*$ethtofiat)-$stat['power-hour']),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_day'] == '1' ) { ?>
                            <li class="list-group-item">Day<span class="pull-right"><?=$fiat['sym'].number_format((($stat['eday']*$ethtofiat)-$stat['power-day']),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_week'] == '1' ) { ?>
                            <li class="list-group-item">Week<span class="pull-right"><?=$fiat['sym'].number_format((($stat['eweek']*$ethtofiat)-$stat['power-week']),2)?></li><?php } ?>
                            <?php if ( $conf['show_month'] == '1' ) { ?>  <li class="list-group-item">Month<span class="pull-right"><?=$fiat['sym'].number_format((($stat['emonth']*$ethtofiat)-$stat['power-month']),2)?></span></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <ul class="list-group">
                            <li class="list-group-item list-group-item-<?=$conf['colour']?>">
                            <h4>฿TC Profitability</h4></li>
                            <?php if ( $conf['show_min'] == '1' ) { ?>
                            <li class="list-group-item">Minute<span class="pull-right"><?=$fiat['sym'].number_format((($stat['bmin']*$btctofiat)-$stat['power-min']),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_hour'] == '1' ) { ?>
                            <li class="list-group-item">Hour<span class="pull-right"><?=$fiat['sym'].number_format((($stat['bhour']*$btctofiat)-$stat['power-hour']),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_day'] == '1' ) { ?>
                            <li class="list-group-item">Day<span class="pull-right"><?=$fiat['sym'].number_format((($stat['bday']*$btctofiat)-$stat['power-day']),2)?></span></li>
                            <?php } ?>
                            <?php if ( $conf['show_week'] == '1' ) { ?>
                            <li class="list-group-item">Week<span class="pull-right"><?=$fiat['sym'].number_format((($stat['bweek']*$btctofiat)-$stat['power-week']),2)?></li><?php } ?>
                            <?php if ( $conf['show_month'] == '1' ) { ?>  <li class="list-group-item">Month<span class="pull-right"><?=$fiat['sym'].number_format((($stat['bmonth']*$btctofiat)-$stat['power-month']),2)?></span></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <?php } ?> -->
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table panel panel-<?=$conf['colour']?>">
                                <thead class="panel-heading">
                                    <tr>
                                        <th class="text-center">Miner</th>
                                        <th class="text-center">Average Hashrate [24h]</th>
                                        <th class="text-center">Reported Hashrate [24h]</th>
                                        <th class="text-center">Last Update</th>
                                    </tr>
                                </thead>
                                <tbody class="panel-body text-center">
                                    <?=$worker->worker_statistics();?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table class="table panel panel-<?=$conf['colour']?> summary">
                                <div class="btn-group show-on-hover pull-right">
                                    <button class="btn btn-<?=$conf['colour']?> dropdown-toggle" type="button" data-toggle="dropdown">
                                    <span class="glyphicon glyphicon-check"></span> Change
                                    <span class="caret"></span>
                                    </button>
                                    <ul class="dropdown-menu" role="menu">
                                        <li><a href="#">USD</a></li>
                                        <li><a href="#">RUR</a></li>
                                    </ul>
                                </div>
                                <thead class="panel-heading">
                                    <tr>
                                        <th class="text-center">Payout</th>
                                        <th class="text-center">and</th>
                                        <th class="text-center">ratio</th>
                                        <th class="text-center">mik</th>
                                        <th class="text-center">ratio</th>
                                        <th class="text-center">p1</th>
                                        <th class="text-center">ratio</th>
                                        <th class="text-center">zdb</th>
                                        <th class="text-center">ratio</th>
                                        <th class="text-center">Amount</th>
                                        <th class="text-center">Edit</th>
                                    </tr>
                                </thead>
                                <tbody class="panel-body text-center">
                                    <?=$payout->payout_statistics();?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!--         <div class="col-md-12">
                        <ul class="list-group">
                            <?php if ( $cache == '1' ) { ?>
                            <li class="list-group-item list-group-item-warning">Using Cached Data: Too many requests to the Ethermine API</li>
                            <?php } ?>
                        </ul>
                    </div> -->
                </div>
                <!-- Please leave this footer block in place, so that others can find ethermine-stats -->
                <div class="container">
                    <div class="col-md-12 footer">
                        <div class="col-md-6">
                            <p class="text-left pull-left">
                                Voloxov:  <span class="pull-right">0x5737A9651cfA22078Ad392fa7DEB187cf55ef50A</span>
                            </p>
                            </br>
                            <p class="text-left pull-left">
                                Minaev:  <span class="pull-right">0x8AA7bA49894d062D10782DaED7b165Ae48F146Ba</span>
                            </p>
                            </br>
                            <p class="text-left pull-left">
                                Konstantin:  <span class="pull-right">0x9B388c66640e9331FdCEd44D1AA5212BD8979182</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p class="text-left">
                                <a href="https://ethermine.org/miners/8AA7bA49894d062D10782DaED7b165Ae48F146Ba" target="_blank" class="pull-right">
                                <i class="fa fa-bar-chart"></i> ETHERMINE-STATS</a>
                            </p>
                            </br>
                            <p class="text-left">
                                <a href="http://ethgasstation.info/calculator.php" target="_blank" class="pull-right">
                                <i class="fa fa-battery-half"></i> ETHERIUM-GAS-PRICE</a>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="edit" tabindex="-1" role="dialog" aria-labelledby="edit" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <form method="post" id="update_form">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></button>
                                    <h4 class="modal-title custom_align" id="Heading">Edit this entry</h4>
                                </div>
                                <div class="modal-body">
                                    <div class="alert alert-warning"><span class="glyphicon glyphicon-warning-sign"></span>  Are you sure you want to paid this Record?</div>
                                    <div class="form-group">
                                        <label>PayOut Id</label>
                                        <input class="form-control " name="payoutid" id="payoutid" type="text" placeholder="Id">
                                    </div>
                                    <div class="form-group">
                                        <label>Worker</label>
                                        <select name="worker" id="worker" class="form-control">
                                            <option value="all">all</option>
                                            <option value="and">and</option>
                                            <option value="mik">mik</option>
                                            <option value="p1">p1</option>
                                            <option value="zdb">zdb</option>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label>PayOut Status</label>
                                        <select name="paidstatus" id="paidstatus" class="form-control">
                                            <option value="paid">paid</option>
                                            <option value="unpaid">unpaid</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer ">
                                    <button type="submit" class="btn btn-success" id="update"><span class="glyphicon glyphicon-ok-sign"></span>Update</button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><span class="glyphicon glyphicon-remove"></span> No</button>
                                </div>
                            </form>
                        </div>
                        <!-- Please leave this footer block in place, so that others can find ethermine-stats -->
                        <?=core_output_footerscripts()?>

                        <script type="text/javascript">
                        $(".editrow").click(function() {
                        var rowid = $(this).closest('tr');
                        // Get the current id
                        var id = parseInt(rowid.find('td:eq(0)').text());
                        $('#payoutid').val(id);
                        $('#paidstatus').val("unpaid");
                        });
                        $('#update_form').on("submit", function(event){
                        event.preventDefault();
                        if($('#payoutid').val() == "")
                        {
                        alert("payoutid is required");
                        }
                        else if($('#paidstatus').val() == '')
                        {
                        alert("paidstatus is required");
                        }
                        else if($('#worker').val() == '')
                        {
                        alert("worker is required");
                        }
                        else
                        {
                        $.ajax({
                        url:"inc/update.php",
                        method:"POST",
                        data:$('#update_form').serialize(),
                        beforeSend:function(){
                        $('#update').val("Updating");
                        },
                        success:function(data){
                        $('#update_form')[0].reset();
                        $('#edit').modal('hide');
                        }
                        });
                        }
                        });
                        </script>
                    </body>
                </html>