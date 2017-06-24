## Need to do
1. Fork 本项目到你仓库
2. 克隆你自己仓库的项目到本地
3. `git remote add esc git@github.com:AISnail/Education.git` 添加远程地址
4. `git fetch esc`
5. `git merge esc/master`
6. https://www.kancloud.cn/manual/thinkphp5/122951

## 获取最后执行的sql语句

```
$model->_sql();
```
方法实际执行的就是
```
$model->getLastSql();
```

