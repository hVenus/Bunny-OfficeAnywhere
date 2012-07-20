<?php
/**
 * ���ݿ���
 * �ṩ���ݿ����ӣ����ݲ�ѯ���������ӣ��޸ģ�ɾ���Ȳ�����
 */

class db
{
    private $conn;
    private $development = 0;

    /**
     * ���캯��
     */
    function __construct(){
        $this->conn = $this->OpenConnection();
        global $DEVELOPMENT;
        $this->development = $DEVELOPMENT;
    }

    function __destruct(){}

    /**
     * ��¼������־
     * @param $data
     */
    private function err_log($data){
        global $ROOT_PATH;
        error_log($data."\r\n", 3, $ROOT_PATH.'temp/php_sql_error.log');
    }

    /**
     * ִ��sql��䣬����resource��
     * @param $sql
     * @return bool|resource
     */
    public function exec($sql){
        if($this->development==1){
            $this->err_log($sql);
        }
        return $this->exequery($this->conn, $sql);
    }

    /**
     * ��ȡһ�м�¼
     * @param $sql sql���
     * @param $type �Ժ�����ʽ��������. ����:MYSQL_ASSOC��MYSQL_NUM �� MYSQL_BOTH
     * @return array|bool
     */
    public function getRow($sql, $type = MYSQL_BOTH){
        $return = false;
        $cursor = $this->exec( $sql );
        if($type == MYSQL_ASSOC || $type == MYSQL_NUM ){
            if( $ROW = mysql_fetch_array( $cursor, $type ) )
            {
                $return = $ROW;
            }
        }else{
            if( $ROW = mysql_fetch_array( $cursor) )
            {
                $return = $ROW;
            }
        }
        return $return;
    }

    /**
     * ��ȡһ�����ݼ�¼
     * @param $sql  sql ���
     * @param $type �Ժ�����ʽ��������. ����:MYSQL_ASSOC��MYSQL_NUM �� MYSQL_BOTH
     * @return array
     */
    public function getRows($sql, $type = MYSQL_BOTH){
         $return = array();
         $cursor = $this->exec( $sql );
         if($type == MYSQL_ASSOC || $type == MYSQL_NUM ){
             while( $ROW = mysql_fetch_array( $cursor, $type ) )
             {
                 $return[] = $ROW;
             }
         }else{
             while( $ROW = mysql_fetch_array( $cursor) )
             {
                 $return[] = $ROW;
             }
         }
         return $return;
     }

    /**
     * �������ݿ�
     * @return resource
     */
    private function OpenConnection( )
    {
        global $connection;
        global $MYSQL_SERVER;
        global $MYSQL_USER;
        global $MYSQL_PASS;
        global $MYSQL_DB;
        global $MYOA_DB_CHARSET;
        if ( !$connection )
        {
            if ( !function_exists( "mysql_pconnect" ) )
            {
                echo _( "PHP�������󣬲��ܵ���Mysql�����⣬�����й�����" );
                exit( );
            }
            $C = @mysql_pconnect( $MYSQL_SERVER, $MYSQL_USER, $MYSQL_PASS, MYSQL_CLIENT_COMPRESS );
        }
        else
        {
            return $connection;
        }
        mysql_query( "SET NAMES ".$MYOA_DB_CHARSET, $C );
        if ( !$C )
        {
            $this->printerror( _( "�������ӵ�MySQL���ݿ⣬���飺1��MySQL�����Ƿ�������2��MySQL������ǽ��ֹ��3������MySQL���û����������Ƿ���ȷ��" ) );
            exit( );
        }
        $result = mysql_select_db( $MYSQL_DB, $C );
        if ( !$result )
        {
            $TIPS = _( "���ݿ� %s ������" );
            $this->printerror( sprintf( $TIPS, $MYSQL_DB ) );
        }
        return $C;
    }

    public function exequery( $C, $Q, $LOG = FALSE )
    {
        if ( !$LOG )
        {
            $POS = stripos( $Q, "union" );
            if ( $POS !== FALSE && stripos( $Q, "select", $POS ) !== FALSE )
            {
                exit( );
            }
            $POS = stripos( $Q, "into" );
            if ( $POS !== FALSE && ( stripos( $Q, "outfile", $POS ) !== FALSE || stripos( $Q, "dumpfile", $POS ) !== FALSE ) )
            {
                exit( );
            }
        }
        if ( gettype( $C ) != "resource" )
        {
            $this->printerror( _( "��Ч�����ݿ�����" )."<br><b>"._( "SQL���:" )."</b> ".$Q, $LOG );
            return FALSE;
        }
        $cursor = @mysql_query( $Q, $C );
        if ( !$cursor )
        {
            $this->printerror( "<b>"._( "SQL���:" )."</b> ".$Q, $LOG );
        }
        return $cursor;
    }

    /**
     * ���������Ϣ
     * @param $MSG
     * @param bool $LOG
     */
    public function printerror( $MSG, $LOG = FALSE )
    {
        global $SCRIPT_FILENAME;
        global $ROOT_PATH;
        echo "<fieldset style=\"line-height:150%;font-size:12px;\">";
        echo "<legend>&nbsp;����ϵ����Ա&nbsp;</legend>";
        echo "<b>"._( "����" )."#".mysql_errno( ).": </b> ".mysql_error( )."<br>";
        echo $MSG."<br>";
        echo "<b>"._( "�ļ���" )."</b>".$SCRIPT_FILENAME;
        if ( mysql_errno( ) == 1030 )
        {
            echo "<br>����ϵ����Ա�� ϵͳ����-���ݿ���� ���޸����ݿ�����" ;
        }
        echo "</fieldset>";
        $LOG_PATH = realpath( $ROOT_PATH."../logs" );
        if ( $LOG )
        {
            if ( file_exists( $LOG_PATH ) && is_writable( $LOG_PATH ) )
            {
                $DATA = date( "[Y-m-d H:i:s]" )."\r\n";
                $DATA .=  "����#" .mysql_errno( ).": ".mysql_error( )."\r\n";
                $DATA .= strip_tags( $MSG )."\r\n";
                $DATA .=  "�ļ���" .$SCRIPT_FILENAME."\r\n";
                $DATA .= "\r\n";
                $LOG_FILE = $LOG_PATH."/mysql_error.log";
                $FP = @fopen( $LOG_FILE, "a" );
                if ( $FP )
                {
                    fwrite( $FP, $DATA );
                    fclose( $FP );
                }
            }
            if ( $LOG )
            {
                exit( );
            }
        }
    }

    /**
     * ����insert ����values()�ַ���
     * @param $data
     * @return string
     */
    public function buildInsertValuesSQL($data){
        $pre_count = 0;
        $pre_values = '';
        $return = "";
        $val = '';
        foreach($data as $d){
            $return .= "(";
            foreach($d as $k=>$v){
                if($pre_count == 0){
                    $pre_values .= $k.',';
                }
                $val .= '"'.$v.'",';

            }
            $return .= trim($val,',').'),';
            $val = '';
            $pre_count++;
        }
        $pre_values = '('.trim($pre_values,',').')values';
        $return = $pre_values.trim($return,',');

        return $return;
    }

    /**
     *  ͨ����������insert���ʹ�õ� set�ַ���.
     * @param $data
     * @return string
     */
    public function buildSetSQL($data){
        $return = "";
        foreach($data as $k=>$v){
            $return .= '`'.$k.'`="'.mysql_real_escape_string(trim($v)).'",';
        }
        return trim($return, ",");
    }

    /**
     * ���� where�Ӿ�ʹ�õ��ַ���
     * @param $where
     * @return string
     */
    public function buildWhereSQL($where){
        $return = "";
        foreach($where as $k=>$v){
            $return .= $k.'="'.mysql_real_escape_string(trim($v)).'" AND';
        }
        return trim($return, "AND");
    }

    /**
     * insert
     * @param $table table name
     * @param $data  data array
     * @return bool|resource
     */
    public function insert($table, $data){
        $sql = 'INSERT INTO '.$table.' SET '.$this->buildSetSQL($data);
        return $this->exec($sql);
    }

    public function update($table, $data, $where){
        $sql = 'UPDATE '.$table.' SET '.$this->buildSetSQL($data).' WHERE '.$this->buildWhereSQL($where);
        return $this->exec($sql);
    }

    public function delete($table, $data){
        $sql = 'DELETE FROM '.$table.' WHERE '.$this->buildWhereSQL($data);
        return $this->exec($sql);
    }

    /**
     * �ж��Ƿ��ǿ������ԡ�
     * @return bool
     */
    public function isDebug(){
        return $this->development==1?true:false;
    }

}

/* End of file db.php */
/* Location: core/db.php */