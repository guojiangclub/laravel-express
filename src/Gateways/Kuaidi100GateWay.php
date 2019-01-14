<?php

namespace iBrand\Express\Gateways;

use GuzzleHttp\Client;
use iBrand\Express\Contracts\ExpressNumberInterface;
use iBrand\Express\Contracts\GatewayInterface;

class Kuaidi100GateWay implements GatewayInterface
{
	const QUERY_URL = 'https://poll.kuaidi100.com/poll/query.do';

	/**
	 * @param \iBrand\Express\Contracts\ExpressNumberInterface $expressNumber
	 * @param array                                            $config
	 *
	 * @return array|mixed
	 * @throws \Exception
	 */
	public function query(ExpressNumberInterface $expressNumber, array $config = [])
	{
		$comCode = $expressNumber->getComCode();
		if (empty($comCode)) {
			throw new \Exception('无效的快递单号');
		}

		$comCode = json_decode($comCode, true);
		if (empty($comCode) || !is_array($comCode)) {
			throw new \Exception('无效的快递单号');
		}

		$result    = [];
		$isSuccess = false;
		foreach ($comCode as $item) {
			$codeInfo = json_encode(["com" => $item['comCode'], "num" => $expressNumber->getNumber()]);

			$params = [
				'customer' => $config['customer'],
				'param'    => $codeInfo,
				'sign'     => strtoupper(md5($codeInfo . $config['key'] . $config['customer'])),
			];

			$params   = $this->ToUrlParams($params);
			$client   = new Client();
			$response = $client->post(self::QUERY_URL . '?' . $params);
			$content  = $response->getBody()->getContents();
			$result   = json_decode($content, true);
			if (!empty($result) && isset($result['message']) && $result['message'] == 'ok' && isset($result['state']) && $result['state'] == 0) {
				$isSuccess = true;
				break;
			}
		}

		if (!$isSuccess) {
			throw new \Exception('快递信息查询失败');
		}

		return $result;
	}

	/**
	 * 格式化参数格式化成url参数
	 *
	 * @param array $config
	 *
	 * @return string
	 */
	public function ToUrlParams(array $config)
	{
		$buff = "";
		foreach ($config as $k => $v) {
			$buff .= "$k=" . urlencode($v) . "&";
		}

		$buff = trim($buff, "&");

		return $buff;
	}
}