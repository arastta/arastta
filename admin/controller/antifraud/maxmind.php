<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2018 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

use Arastta\Component\Form\Form as AForm;

class ControllerAntifraudMaxmind extends Controller
{

    private $error = array();

    public function index()
    {
        $this->load->language('antifraud/maxmind');

        $this->document->setTitle($this->language->get('heading_title'));

        $this->load->model('setting/setting');

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->model_setting_setting->editSetting('maxmind_antifraud', $this->request->post);

            $this->session->data['success'] = $this->language->get('text_success');

            if (isset($this->request->post['button']) && $this->request->post['button'] == 'save') {
                $this->response->redirect($this->url->link($this->request->get['route'], 'token=' . $this->session->data['token'], 'SSL'));
            }

            $this->response->redirect($this->url->link('extension/extension', 'filter_type=antifraud&token=' . $this->session->data['token'], 'SSL'));
        }

        // Add all language text
        $data = $this->language->all();

        if (isset($this->error['warning'])) {
            $data['error_warning'] = $this->error['warning'];
        } else {
            $data['error_warning'] = '';
        }

        $data['action'] = $this->url->link('antifraud/maxmind', 'token=' . $this->session->data['token'], 'SSL');

        $data['cancel'] = $this->url->link('extension/extension', 'filter_type=antifraud&token=' . $this->session->data['token'], 'SSL');

        $data['form_fields'] = $this->getFormFields($data['action']);

        $data['header'] = $this->load->controller('common/header');
        $data['column_left'] = $this->load->controller('common/column_left');
        $data['footer'] = $this->load->controller('common/footer');

        $this->response->setOutput($this->load->output('antifraud/maxmind', $data));
    }

    public function order()
    {
        $maxmind_status = $this->config->get('maxmind_antifraud_status', 0);

        // Disable Maxmind Fraud extension continue
        if (!$maxmind_status) {
            return false;
        }

        $this->load->language('antifraud/maxmind');

        $this->load->model('sale/fraud');

        if (isset($this->request->get['order_id'])) {
            $order_id = $this->request->get['order_id'];
        } else {
            $order_id = 0;
        }

        $fraud_info = $this->model_sale_fraud->getFraud($order_id);

        // Add all language text
        $data = $this->language->all();

        if ($fraud_info) {
            $data['country_match'] = $fraud_info['country_match'];

            if ($fraud_info['country_code']) {
                $data['country_code'] = $fraud_info['country_code'];
            } else {
                $data['country_code'] = '';
            }

            $data['high_risk_country'] = $fraud_info['high_risk_country'];
            $data['distance'] = $fraud_info['distance'];

            if ($fraud_info['ip_region']) {
                $data['ip_region'] = $fraud_info['ip_region'];
            } else {
                $data['ip_region'] = '';
            }

            if ($fraud_info['ip_city']) {
                $data['ip_city'] = $fraud_info['ip_city'];
            } else {
                $data['ip_city'] = '';
            }

            $data['ip_latitude'] = $fraud_info['ip_latitude'];
            $data['ip_longitude'] = $fraud_info['ip_longitude'];

            if ($fraud_info['ip_isp']) {
                $data['ip_isp'] = $fraud_info['ip_isp'];
            } else {
                $data['ip_isp'] = '';
            }

            if ($fraud_info['ip_org']) {
                $data['ip_org'] = $fraud_info['ip_org'];
            } else {
                $data['ip_org'] = '';
            }

            $data['ip_asnum'] = $fraud_info['ip_asnum'];

            if ($fraud_info['ip_user_type']) {
                $data['ip_user_type'] = $fraud_info['ip_user_type'];
            } else {
                $data['ip_user_type'] = '';
            }

            if ($fraud_info['ip_country_confidence']) {
                $data['ip_country_confidence'] = $fraud_info['ip_country_confidence'];
            } else {
                $data['ip_country_confidence'] = '';
            }

            if ($fraud_info['ip_region_confidence']) {
                $data['ip_region_confidence'] = $fraud_info['ip_region_confidence'];
            } else {
                $data['ip_region_confidence'] = '';
            }

            if ($fraud_info['ip_city_confidence']) {
                $data['ip_city_confidence'] = $fraud_info['ip_city_confidence'];
            } else {
                $data['ip_city_confidence'] = '';
            }

            if ($fraud_info['ip_postal_confidence']) {
                $data['ip_postal_confidence'] = $fraud_info['ip_postal_confidence'];
            } else {
                $data['ip_postal_confidence'] = '';
            }

            if ($fraud_info['ip_postal_code']) {
                $data['ip_postal_code'] = $fraud_info['ip_postal_code'];
            } else {
                $data['ip_postal_code'] = '';
            }

            $data['ip_accuracy_radius'] = $fraud_info['ip_accuracy_radius'];

            if ($fraud_info['ip_net_speed_cell']) {
                $data['ip_net_speed_cell'] = $fraud_info['ip_net_speed_cell'];
            } else {
                $data['ip_net_speed_cell'] = '';
            }

            $data['ip_metro_code'] = $fraud_info['ip_metro_code'];
            $data['ip_area_code'] = $fraud_info['ip_area_code'];

            if ($fraud_info['ip_time_zone']) {
                $data['ip_time_zone'] = $fraud_info['ip_time_zone'];
            } else {
                $data['ip_time_zone'] = '';
            }

            if ($fraud_info['ip_region_name']) {
                $data['ip_region_name'] = $fraud_info['ip_region_name'];
            } else {
                $data['ip_region_name'] = '';
            }

            if ($fraud_info['ip_domain']) {
                $data['ip_domain'] = $fraud_info['ip_domain'];
            } else {
                $data['ip_domain'] = '';
            }

            if ($fraud_info['ip_country_name']) {
                $data['ip_country_name'] = $fraud_info['ip_country_name'];
            } else {
                $data['ip_country_name'] = '';
            }

            if ($fraud_info['ip_continent_code']) {
                $data['ip_continent_code'] = $fraud_info['ip_continent_code'];
            } else {
                $data['ip_continent_code'] = '';
            }

            if ($fraud_info['ip_corporate_proxy']) {
                $data['ip_corporate_proxy'] = $fraud_info['ip_corporate_proxy'];
            } else {
                $data['ip_corporate_proxy'] = '';
            }

            $data['anonymous_proxy'] = $fraud_info['anonymous_proxy'];
            $data['proxy_score'] = $fraud_info['proxy_score'];

            if ($fraud_info['is_trans_proxy']) {
                $data['is_trans_proxy'] = $fraud_info['is_trans_proxy'];
            } else {
                $data['is_trans_proxy'] = '';
            }

            $data['free_mail'] = $fraud_info['free_mail'];
            $data['carder_email'] = $fraud_info['carder_email'];

            if ($fraud_info['high_risk_username']) {
                $data['high_risk_username'] = $fraud_info['high_risk_username'];
            } else {
                $data['high_risk_username'] = '';
            }

            if ($fraud_info['high_risk_password']) {
                $data['high_risk_password'] = $fraud_info['high_risk_password'];
            } else {
                $data['high_risk_password'] = '';
            }

            $data['bin_match'] = $fraud_info['bin_match'];

            if ($fraud_info['bin_country']) {
                $data['bin_country'] = $fraud_info['bin_country'];
            } else {
                $data['bin_country'] = '';
            }

            $data['bin_name_match'] = $fraud_info['bin_name_match'];

            if ($fraud_info['bin_name']) {
                $data['bin_name'] = $fraud_info['bin_name'];
            } else {
                $data['bin_name'] = '';
            }

            $data['bin_phone_match'] = $fraud_info['bin_phone_match'];

            if ($fraud_info['bin_phone']) {
                $data['bin_phone'] = $fraud_info['bin_phone'];
            } else {
                $data['bin_phone'] = '';
            }

            if ($fraud_info['customer_phone_in_billing_location']) {
                $data['customer_phone_in_billing_location'] = $fraud_info['customer_phone_in_billing_location'];
            } else {
                $data['customer_phone_in_billing_location'] = '';
            }

            $data['ship_forward'] = $fraud_info['ship_forward'];

            if ($fraud_info['city_postal_match']) {
                $data['city_postal_match'] = $fraud_info['city_postal_match'];
            } else {
                $data['city_postal_match'] = '';
            }

            if ($fraud_info['ship_city_postal_match']) {
                $data['ship_city_postal_match'] = $fraud_info['ship_city_postal_match'];
            } else {
                $data['ship_city_postal_match'] = '';
            }

            $data['score'] = $fraud_info['score'];
            $data['explanation'] = $fraud_info['explanation'];
            $data['risk_score'] = $fraud_info['risk_score'];
            $data['queries_remaining'] = $fraud_info['queries_remaining'];
            $data['maxmind_id'] = $fraud_info['maxmind_id'];
            $data['error'] = $fraud_info['error'];
        } else {
            $data['maxmind_id'] = '';
        }

        return $this->load->view('antifraud/maxmind_info.tpl', $data);
    }

    protected function getFormFields($action)
    {
        $action = str_replace('amp;', '', $action);

        $key['value'] = $this->config->get('maxmind_antifraud_key', '');
        $key['placeholder'] = $this->language->get('entry_key');
        $key['required'] = $this->language->get('required');

        $score['value'] = $this->config->get('maxmind_antifraud_score', '');
        $score['placeholder'] = $this->language->get('entry_score');
        $score['required'] = $this->language->get('required');

        $this->load->model('localisation/order_status');

        $order_option = array();
        $order_statuses = $this->model_localisation_order_status->getOrderStatuses();

        foreach ($order_statuses as $order_status) {
            $order_option[$order_status['order_status_id']] = $order_status['name'];
        }

        $order_text = array(
            'value' => $this->config->get('maxmind_antifraud_order_status_id', 0),
            'selected'  => $this->config->get('maxmind_antifraud_order_status_id', 0)
        );

        $status_text = array(
            'yes' => $this->language->get('text_enabled'),
            'no'  => $this->language->get('text_disabled')
        );

        $status['value'] = $this->config->get('maxmind_antifraud_status', 0);
        $status['labelclass'] = 'radio-inline';

        $form = new AForm('form-maxmind-antifraud', $action);

        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_key'), 'maxmind_antifraud_key', $key));
        $form->addElement(new Arastta\Component\Form\Element\Textbox($this->language->get('entry_score'), 'maxmind_antifraud_score', $score));
        $form->addElement(new Arastta\Component\Form\Element\Select($this->language->get('entry_order'), 'maxmind_antifraud_order_status_id', $order_option, $order_text));
        $form->addElement(new Arastta\Component\Form\Element\YesNo($this->language->get('entry_status'), 'maxmind_antifraud_status', $status, $status_text));

        return $form->render(true);
    }

    protected function validate()
    {
        if (!$this->user->hasPermission('modify', 'antifraud/maxmind')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (empty($this->request->post['maxmind_antifraud_key'])) {
            $this->error['warning'] = $this->language->get('error_key');
        }

        if (empty($this->request->post['maxmind_antifraud_score'])) {
            $this->error['warning'] = $this->language->get('error_score');
        }

        return !$this->error;
    }
}
