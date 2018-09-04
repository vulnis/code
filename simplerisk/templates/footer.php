<!-- start ./templates/footer.php -->
<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
<script src="/js/bootstrap-datepicker.min.js"></script>
<script src="/js/popper.min.js"></script>
<!--<script src="/js/jquery.easyui.min.js"></script>
<script src="/js/jquery-ui.min.js"></script>
<script src="/js/jquery.draggable.js"></script>
<script src="/js/jquery.droppable.js"></script>
<script src="/js/treegrid-dnd.js"></script>
<script src="/js/bootstrap-multiselect.js"></script>
<script src="/js/jquery.dataTables.js"></script>
<script src="/js/pages/governance.js"></script>-->
<script>
    var BASE_URL = '<?php (isset($_SESSION['base_url']) ? $_SESSION['base_url'] : "") ?>';
    var simplerisk = {
        allSelectedText: '<?php echo $escaper->escapeHtml($lang['AllSelectedText']); ?>',
        allFrameworks: '<?php echo $escaper->escapeHtml($lang['AllFrameworks']); ?>',
        all: '<?php echo $escaper->escapeHtml($lang['ALL']); ?>'
    }

    <?php if (isset($_POST['edit_mitigation'])): ?>
        simplerisk.editmitigation = 0;
    <?php elseif (!isset($_POST['tab_type']) && (isset($_POST['edit_details']) ||(isset($_GET['type']) && $_GET['type']) =='0')): ?>
        simplerisk.editmitigation = 0;
    <?php elseif ((isset($_POST['tab_type']) || isset($_GET['tab_type'])) || isset($_GET['type']) && $_GET['type']=='1'): ?>
        simplerisk.editmitigation = 1;
    <?php else: ?>
        simplerisk.editmitigation = 2;
    <?php endif; ?>
</script>
<script src="/js/simplerisk.js"></script>
<!-- end ./templates/footer.php -->
</body>
</html>
