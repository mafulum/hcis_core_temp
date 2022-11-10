var TreeView = function () {

    return {
        //main function to initiate the module
        init: function () {

            var DataSourceTree = function (options) {
                this._data  = options.data;
                this._delay = options.delay;
            };

            DataSourceTree.prototype = {

                data: function (options, callback) {
                    var self = this;

                    setTimeout(function () {
                        var data = $.extend(true, [], self._data);

                        callback({ data: data });

                    }, this._delay)
                }
            };

            // INITIALIZING TREE
            var treeDataSource = new DataSourceTree({
                data: [
                    { name: 'PIHC', type: 'folder', additionalParameters: { id: 'F1' } },
                    { name: 'PKG', type: 'folder', additionalParameters: { id: 'F2' } },
                    { name: 'PKT', type: 'folder', additionalParameters: { id: 'F3' } },
                    { name: 'PKC', type: 'folder', additionalParameters: { id: 'F4' } },
                    { name: 'PSP', type: 'folder', additionalParameters: { id: 'F5' } },
                    { name: 'PIM', type: 'folder', additionalParameters: { id: 'F6' } },
                    { name: 'Rekind', type: 'folder', additionalParameters: { id: 'F7' } },
                    { name: 'ME', type: 'folder', additionalParameters: { id: 'F8' } },
                    { name: 'Item 1', type: 'item', additionalParameters: { id: 'I1' } },
                    { name: 'Item 2', type: 'item', additionalParameters: { id: 'I2' } }
                ],
                delay: 400
            });

            $('#FlatTree').tree({
                dataSource: treeDataSource
                //,loadingHTML: '<img src="img/input-spinner.gif"/>'
            });

        }

    };

}();