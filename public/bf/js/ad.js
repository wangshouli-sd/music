/**
 * adblock检测
 * 
 * @author J.Soon <serdeemail@gmail.com>
 */
(function fuckadblock() {

    var adblockId = 'FKADB_UwyDnVCiSkce'; // 约定好的随机id（随机命名尽可能避免id冲突）

    if (!document.getElementById(adblockId)) {
        var ele = document.createElement('div');
        ele.id = adblockId;
        ele.style.display = 'none';
        document.body.appendChild(ele);
    }

})();