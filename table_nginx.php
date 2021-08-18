<?php include 'getCSV.php'; 
    include './assets/component/head_nav.php';
?>

        <div class="flex-column wrap pt-3 m-5">
            <div class="table card p-4 shadow">

                <h4 class="text-center">Logs nginx</h4>
                <form action="<?php echo $_SERVER["PHP_SELF"]; ?>" method="post" class="mb-3 d-flex justify-content-end">
                    <button type="submit" name="csvExport_nginx" value="Export to CSV" class="btn btn-primary btn-sm">Export csv</button>
                </form>

                <table id="example" class="display nowrap " style="width:100%">
                    <thead>
                        <tr>
                            <th>Remote Address</th>
                            <!-- <th>Remote User</th>
                        <th>Remote </th> -->
                            <th>Time Local</th>
                            <th>Request</th>
                            <th>Status</th>
                            <th>Body bytes sent</th>
                            <th>HTTP User Agent</th>
                            <th>rt</th>
                            <th>uct</th>
                            <th>uht</th>
                            <th>urt</th>
                            <th>gz</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($log = mysqli_fetch_array($logs_nginx)) { ?>
                            <tr>

                                <td><?php echo $log['remote_address'] ?></td>
                                <!-- <td><?php echo $log['remote_user'] ?></td> -->
                                <!-- <td><?php echo $log['remote'] ?></td> -->
                                <td><?php echo $log['time_local'] ?></td>
                                <td><?php echo $log['request'] ?></td>
                                <td><?php echo $log['status'] ?></td>
                                <td><?php echo $log['body_bytes_sent'] ?></td>
                                <td><?php echo $log['http_referer'] ?></td>
                                <td><?php echo $log['rt'] ?></td>
                                <td><?php echo $log['uct'] ?></td>
                                <td><?php echo $log['uht'] ?></td>
                                <td><?php echo $log['urt'] ?></td>
                                <td><?php echo $log['gz'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#example').DataTable({
                "scrollX": true,
                pagingType: $(window).width() < 600 ? "simple" : "full"
            });
        });
    </script>
</body>

</html>