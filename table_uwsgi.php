<?php 
include 'getCSV.php'; 
include './assets/component/head_nav.php'
?>

    <div  class="flex-column pt-3 m-5 " >
    <div class="table card p-4 shadow">

    <h4 class="text-center">Logs  uwsgi</h4>
    <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="mb-3 d-flex justify-content-end" >
        <button type="submit" name="csvExport_uwsgi" value="Export to CSV" 
        class="btn btn-primary btn-sm ">Export csv</button>
    </form>
        <table id="example" class="display wrap" style="width:100%">
            <thead>
                <tr>
                    <th>Address space usage(bytes)</th>
                    <th>Address space (MB)</th>
                    <th>Rss usage (bytes</th>
                    <th>Rss total (MB)</th>
                    <th>Pid</th>
                    <!-- <th>App</th>
                    <th>Req</th> -->
                </tr>
            </thead>
            <tbody>

                <?php while ($log = mysqli_fetch_array($logs_uwsgi)) {
                ?>
                    <tr>
                        <td><?php echo $log['address_space_usage'] ?></td>
                        <td><?php echo $log['address_space'] ?></td>
                        <td><?php echo $log['rss_usage'] ?></td>
                        <td><?php echo $log['rss'] ?></td>
                        <td><?php echo $log['pid'] ?></td>
                        <!-- <td><?php echo $log['app'] ?></td>
                        <td><?php echo $log['req'] ?></td> -->
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>
    </div>
    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable({
                scrollX: true,
                // "responsive":true,
                pagingType: $(window).width() < 600 ? "simple" : "full"
            });
        });
    </script>
</div>
</body>

</html>