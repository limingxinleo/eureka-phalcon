# PHALCON基础开发框架

> 本项目以[limingxinleo/phalcon-project](https://github.com/limingxinleo/phalcon)为基础，进行简易封装。

[![Build Status](https://travis-ci.org/limingxinleo/eureka-phalcon.svg?branch=master)](https://travis-ci.org/limingxinleo/eureka-phalcon)
[![Total Downloads](https://poser.pugx.org/limingxinleo/phalcon-project/downloads)](https://packagist.org/packages/limingxinleo/phalcon-project)
[![Latest Stable Version](https://poser.pugx.org/limingxinleo/phalcon-project/v/stable)](https://packagist.org/packages/limingxinleo/phalcon-project)
[![Latest Unstable Version](https://poser.pugx.org/limingxinleo/phalcon-project/v/unstable)](https://packagist.org/packages/limingxinleo/phalcon-project)
[![License](https://poser.pugx.org/limingxinleo/phalcon-project/license)](https://packagist.org/packages/limingxinleo/phalcon-project)


[Phalcon 官网](https://docs.phalconphp.com/zh/latest/index.html)

[wiki](https://github.com/limingxinleo/simple-subcontrollers.phalcon/wiki)

### 封装版本
- [Thrift GO服务版本](https://github.com/limingxinleo/thrift-go-phalcon-project)
- [Phalcon快速开发框架](https://github.com/limingxinleo/biz-phalcon)
- [Phalcon基础开发框架](https://github.com/limingxinleo/basic-phalcon)
- [Zipkin开发版本](https://github.com/limingxinleo/zipkin-phalcon)
- [Eureka开发版本](https://github.com/limingxinleo/eureka-phalcon)
- [RabbitMQ](https://github.com/limingxinleo/rabbitmq-phalcon)

### 测试以及其他DEMO
- [框架测试](https://github.com/limingxinleo/phalcon-unit-test)
- [多库单表](https://github.com/limingxinleo/service-demo-order)
- [Elasticsearch](https://github.com/Aquarmini/elasticsearch-demo-phalcon)
- [kafka](https://github.com/Aquarmini/kafka-demo-phalcon)
- [机器学习](https://github.com/Aquarmini/ml-demo-phalcon)
- [正则匹配](https://github.com/Aquarmini/regex-demo-phalcon)

### 使用说明

安装
~~~
docker pull limingxinleo/docker-eureka:1.1.147
~~~

启动
~~~
docker run --name eureka -p 8081:8080 -d limingxinleo/docker-eureka:1.1.147
~~~

配置
~~~
# .env文件
APP_NAME=phalcon  # 服务名
APP_URL=eureka.phalcon.xin  # 服务接口调用地址
EUREKA_BASE_URI=http://127.0.0.1:8081/  # Eureka地址
~~~
