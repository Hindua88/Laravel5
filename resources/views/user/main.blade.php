<!DOCTYPE html>
<html>
    <head>
        <title>Laravel</title>

        <link href="https://fonts.googleapis.com/css?family=Lato:100" rel="stylesheet" type="text/css">

        <style>
            html, body {
                height: 100%;
            }

            body {
                margin: 0;
                padding: 0;
                width: 100%;
                display: table;
                font-weight: 100;
                font-family: 'Lato';
            }

            .container {
                text-align: center;
                display: table-cell;
            }

            .content {
                text-align: center;
                display: inline-block;
            }

            .title {
                font-size: 48px;
            }
            
            .table_step {
                border-collapse: collapse;
            }

            table, th, td {
                border: 1px solid #ddd;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="content">
                <div class="title">Animal</div>
                <?php $count = 1; ?>
                <?php foreach ($world->histories as $history): ?>
                    <div "step">
                        <h3>Step <?php echo $count ++ ?></h3>
                        <table class="table_step">
                            <?php for ($i = 0; $i < $world->m; $i ++): ?>
                                <tr>
                                <?php for ($j = 0; $j < $world->n; $j ++): ?>
                                    <td width="<?php echo $cell_width; ?>" height="<?php echo $cell_height; ?>" >
                                        <?php $key = $i . '_' . $j; ?>
                                        <?php $data = @$history[$key]; ?>
                                        <?php if ($data): ?>
                                            <img width="<?php echo $cell_width; ?>" 
                                                height="<?php echo $cell_height ?>" src="<?php echo $data->image; ?>" />
                                        <?php endif ?>
                                    </td>
                                <?php endfor ?>
                                </tr>
                           <?php endfor ?>
                        </table>
                    </div>
                <?php endforeach ?>
            </div>
        </div>
    </body>
</html>
